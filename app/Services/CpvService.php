<?php

namespace App\Services;

use App\Models\CpvCode;
use App\Models\CpvSynonym;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CpvService
{
    /**
     * Suggest CPV codes based on input.
     */
    public function suggest(array $input): array
    {
        $cacheKey = 'cpv:' . sha1(json_encode($input));

        return Cache::remember($cacheKey, now()->addHours(24), function() use ($input) {
            $text = $this->normalize($input);

            if (empty($text)) {
                return $this->formatResponse([], $input, ['No text provided for analysis']);
            }

            try {
                $prompt = $this->buildPrompt($text, $input['language'] ?? 'de');
                $response = $this->callAnthropic($prompt);
                $candidates = $this->parseCandidates($response);
                $validated = $this->validateAgainstDb($candidates);

                return $this->formatResponse($validated, $input);
            } catch (\Exception $e) {
                Log::error('CPV suggestion failed: ' . $e->getMessage(), [
                    'input' => $input,
                    'trace' => $e->getTraceAsString(),
                ]);

                return $this->formatResponse([], $input, ['Service temporarily unavailable']);
            }
        });
    }

    /**
     * Normalize input text from description or URL.
     */
    private function normalize(array $input): string
    {
        $text = '';

        // If URL is provided, fetch content
        if (!empty($input['url'])) {
            $text .= $this->fetchUrlContent($input['url']) . "\n\n";
        }

        // Add description
        if (!empty($input['description'])) {
            $text .= $input['description'];
        }

        return trim($text);
    }

    /**
     * Fetch and extract text content from URL.
     */
    private function fetchUrlContent(string $url): string
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'CPV-Suggest-Service/1.0',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();

                // Basic HTML to text conversion
                $text = strip_tags($html);
                $text = preg_replace('/\s+/', ' ', $text);

                // Limit to first 3000 characters
                return substr($text, 0, 3000);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch URL: ' . $url, ['error' => $e->getMessage()]);
        }

        return '';
    }

    /**
     * Build the LLM prompt for CPV code suggestion.
     */
    private function buildPrompt(string $text, string $language): string
    {
        $instructions = $language === 'de'
            ? 'Analysiere die folgende Unternehmensbeschreibung und schlage passende CPV-Codes vor.'
            : 'Analyze the following company description and suggest appropriate CPV codes.';

        return <<<PROMPT
$instructions

CPV (Common Procurement Vocabulary) codes are used to classify public procurement contracts.

Based on the description, identify the most relevant CPV codes. Return your response as valid JSON with this exact structure:

{
  "candidates": [
    {"cpv": "72000000", "label": "IT services", "confidence": 0.95, "rationale": "Brief explanation"},
    {"cpv": "72222300", "label": "IT consulting", "confidence": 0.87, "rationale": "Brief explanation"}
  ],
  "rationale": "Overall explanation of why these codes were selected"
}

Guidelines:
- Use 8-digit CPV codes (e.g., "72222300")
- Confidence should be between 0.0 and 1.0
- Suggest 5-15 codes, ordered by relevance
- Focus on services and products mentioned in the text

Company Description:
$text

Return only the JSON, no additional text.
PROMPT;
    }

    /**
     * Call Anthropic Claude API.
     */
    private function callAnthropic(string $prompt): array
    {
        $apiKey = config('services.anthropic.key');
        $model = config('services.anthropic.model', 'claude-3-5-sonnet-20241022');
        $url = config('services.anthropic.url', 'https://api.anthropic.com/v1/messages');

        if (empty($apiKey)) {
            throw new \RuntimeException('Anthropic API key not configured');
        }

        $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])
            ->retry(3, 250)
            ->timeout(30)
            ->post($url, [
                'model' => $model,
                'max_tokens' => 2048,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
            ]);

        if (!$response->successful()) {
            throw new \RuntimeException('Anthropic API request failed: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Parse candidates from Anthropic response.
     */
    private function parseCandidates(array $response): array
    {
        try {
            if (!isset($response['content'][0]['text'])) {
                return [];
            }

            $text = $response['content'][0]['text'];

            // Extract JSON from response (handle markdown code blocks)
            if (preg_match('/```json\s*(.*?)\s*```/s', $text, $matches)) {
                $jsonText = $matches[1];
            } elseif (preg_match('/```\s*(.*?)\s*```/s', $text, $matches)) {
                $jsonText = $matches[1];
            } else {
                $jsonText = $text;
            }

            $data = json_decode($jsonText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to parse Anthropic JSON response', ['text' => $text]);
                return [];
            }

            return [
                'candidates' => $data['candidates'] ?? [],
                'rationale' => $data['rationale'] ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to parse Anthropic response', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Validate candidates against CPV database.
     */
    private function validateAgainstDb(array $parsed): array
    {
        $out = [];
        $candidates = $parsed['candidates'] ?? [];

        foreach ($candidates as $candidate) {
            $cpvCode = $candidate['cpv'] ?? null;
            $label = $candidate['label'] ?? '';
            $confidence = $candidate['confidence'] ?? 0.5;
            $rationale = $candidate['rationale'] ?? '';

            // Try direct code lookup
            if (!empty($cpvCode)) {
                $code = CpvCode::find($cpvCode);
                if ($code) {
                    $out[] = [
                        'cpv' => $code->code,
                        'cpv_full' => $code->full_code,
                        'check_digit' => $code->check_digit,
                        'title' => $code->title,
                        'level' => $code->level,
                        'path' => $this->formatPath($code->getPath()),
                        'confidence' => $confidence,
                        'source' => 'llm+validated',
                        'rationale' => $rationale,
                    ];
                    continue;
                }
            }

            // Try synonym matching
            if (!empty($label)) {
                $match = CpvSynonym::match($label);
                if ($match && $match->cpvCode) {
                    $out[] = [
                        'cpv' => $match->cpvCode->code,
                        'cpv_full' => $match->cpvCode->full_code,
                        'check_digit' => $match->cpvCode->check_digit,
                        'title' => $match->cpvCode->title,
                        'level' => $match->cpvCode->level,
                        'path' => $this->formatPath($match->cpvCode->getPath()),
                        'confidence' => $confidence * 0.7, // Reduce confidence for synonym matches
                        'source' => 'synonym',
                        'rationale' => $rationale,
                    ];
                }
            }
        }

        return [
            'codes' => $out,
            'rationale' => $parsed['rationale'] ?? '',
        ];
    }

    /**
     * Format hierarchical path.
     */
    private function formatPath(array $path): array
    {
        return array_map(function($item) {
            return $item['code'] . ' - ' . $item['title'];
        }, $path);
    }

    /**
     * Format final response.
     */
    private function formatResponse(array $validated, array $input, array $warnings = []): array
    {
        $topK = $input['top_k'] ?? 12;
        $specificity = $input['specificity'] ?? null;
        $codes = $validated['codes'] ?? [];

        // Remove duplicates and sort by confidence
        $unique = collect($codes)
            ->unique('cpv')
            ->sortByDesc('confidence')
            ->values();

        // Apply specificity filtering if requested
        if ($specificity !== null && in_array($specificity, [1, 2, 3])) {
            $unique = $this->applySpecificityFilter($unique, $specificity);
        }

        $result = $unique->take($topK)->all();

        return [
            'query_id' => bin2hex(random_bytes(8)),
            'language_detected' => $input['language'] ?? null,
            'codes' => $result,
            'rationale' => $validated['rationale'] ?? '',
            'warnings' => $warnings,
            'cached' => false, // Updated by cache mechanism
        ];
    }

    /**
     * Apply specificity filtering to codes.
     *
     * @param \Illuminate\Support\Collection $codes
     * @param int $specificity 1=specific (high levels), 2=medium, 3=general (low levels)
     * @return \Illuminate\Support\Collection
     */
    private function applySpecificityFilter($codes, int $specificity)
    {
        $totalCount = $codes->count();
        $minimumCount = (int) ceil($totalCount * 0.5);

        // Step 1: Parent-child deduplication
        $deduplicated = $this->deduplicateParentChild($codes, $specificity);

        // Step 2: Level filtering with dynamic expansion to meet minimum
        $filtered = $this->filterByLevelWithMinimum($deduplicated, $specificity, $minimumCount);

        return $filtered;
    }

    /**
     * Remove parent-child duplicates based on specificity.
     */
    private function deduplicateParentChild($codes, int $specificity)
    {
        $result = collect();
        $codeMap = $codes->keyBy('cpv');

        foreach ($codes as $code) {
            $cpvCode = $code['cpv'];
            $shouldInclude = true;

            // Check if this code has a parent or child in the list
            $parentCode = $this->calculateParentCode($cpvCode);
            $hasParentInList = $parentCode && $codeMap->has($parentCode);

            $hasChildInList = $codeMap->keys()->contains(function($otherCode) use ($cpvCode) {
                return $this->isParentOf($cpvCode, $otherCode);
            });

            // Decide based on specificity
            if ($specificity === 1) {
                // Specific: prefer children, exclude parents
                if ($hasChildInList) {
                    $shouldInclude = false;
                }
            } elseif ($specificity === 3) {
                // General: prefer parents, exclude children
                if ($hasParentInList) {
                    $shouldInclude = false;
                }
            }
            // specificity === 2: keep both (medium, no deduplication)

            if ($shouldInclude) {
                $result->push($code);
            }
        }

        return $result;
    }

    /**
     * Filter by level with dynamic expansion to meet minimum count.
     */
    private function filterByLevelWithMinimum($codes, int $specificity, int $minimumCount)
    {
        // Define preferred level ranges
        $levelRanges = [
            1 => [5, 4, 3, 2, 1], // Specific: start with high levels
            2 => [3, 4, 2, 5, 1], // Medium: prefer middle levels
            3 => [1, 2, 3, 4, 5], // General: start with low levels
        ];

        $preferredOrder = $levelRanges[$specificity];
        $result = collect();

        // Try each level in preferred order until we have enough
        foreach ($preferredOrder as $level) {
            $atLevel = $codes->filter(function($code) use ($level) {
                return $code['level'] == $level;
            });

            $result = $result->merge($atLevel);

            if ($result->count() >= $minimumCount) {
                break;
            }
        }

        // If still not enough, add remaining codes
        if ($result->count() < $minimumCount) {
            $remaining = $codes->reject(function($code) use ($result) {
                return $result->contains('cpv', $code['cpv']);
            });
            $result = $result->merge($remaining);
        }

        return $result;
    }

    /**
     * Calculate parent code from a CPV code string.
     */
    private function calculateParentCode(string $code): ?string
    {
        $trimmed = rtrim($code, '0');
        if (strlen($trimmed) <= 2) {
            return null;
        }
        $parentTrimmed = substr($trimmed, 0, -1) . '0';
        return str_pad($parentTrimmed, 8, '0');
    }

    /**
     * Check if $parentCode is a parent (direct or ancestor) of $childCode.
     */
    private function isParentOf(string $parentCode, string $childCode): bool
    {
        if ($parentCode === $childCode) {
            return false;
        }

        // Check if parentCode is anywhere in the ancestry chain
        $current = $childCode;
        while ($current !== null) {
            $calculated = $this->calculateParentCode($current);
            if ($calculated === null) {
                break;
            }
            if ($calculated === $parentCode) {
                return true;
            }
            $current = $calculated;
        }

        return false;
    }
}
