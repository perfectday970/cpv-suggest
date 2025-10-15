<?php

namespace App\Http\Controllers;

use App\Models\Bekanntmachung;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BekanntmachungController extends Controller
{
    /**
     * Search for Bekanntmachungen by CPV codes.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cpv_codes' => 'required|array|min:1',
            'cpv_codes.*' => 'required|string|size:8',
            'published_after' => 'nullable|date',
            'published_before' => 'nullable|date',
            'deadline_after' => 'nullable|date',
            'typ' => 'nullable|string',
            'min_value' => 'nullable|numeric|min:0',
            'max_value' => 'nullable|numeric|min:0',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $cpvCodes = $request->input('cpv_codes');
        $limit = $request->input('limit', 20);

        // Start query
        $query = Bekanntmachung::query()
            ->with('cpvCodes')
            ->withCpvCodes($cpvCodes);

        // Apply optional filters
        if ($request->has('published_after') && $request->has('published_before')) {
            $query->publishedBetween(
                $request->input('published_after'),
                $request->input('published_before')
            );
        } elseif ($request->has('published_after')) {
            $query->where('veroeffentlicht', '>=', $request->input('published_after'));
        } elseif ($request->has('published_before')) {
            $query->where('veroeffentlicht', '<=', $request->input('published_before'));
        }

        if ($request->has('deadline_after')) {
            $query->deadlineAfter($request->input('deadline_after'));
        }

        if ($request->has('typ')) {
            $query->where('typ', $request->input('typ'));
        }

        if ($request->has('min_value')) {
            $query->where('geschaetzter_auftragswert', '>=', $request->input('min_value'));
        }

        if ($request->has('max_value')) {
            $query->where('geschaetzter_auftragswert', '<=', $request->input('max_value'));
        }

        // Order by publication date (newest first)
        $query->orderBy('veroeffentlicht', 'desc');

        // Execute query
        $bekanntmachungen = $query->limit($limit)->get();

        // Format response
        $results = $bekanntmachungen->map(function ($item) {
            return [
                'id' => $item->id,
                'veroeffentlicht' => $item->veroeffentlicht->format('Y-m-d'),
                'angebotsfrist' => $item->angebotsfrist?->format('Y-m-d'),
                'kurzbezeichnung' => $item->kurzbezeichnung,
                'typ' => $item->typ,
                'vergabeplattform' => $item->vergabeplattform,
                'geschaetzter_auftragswert' => $item->geschaetzter_auftragswert,
                'beschreibung' => $item->beschreibung,
                'cpv_codes' => $item->cpvCodes->map(function ($cpv) {
                    return [
                        'code' => $cpv->code,
                        'title' => $cpv->title,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $results->count(),
            'data' => $results,
        ]);
    }

    /**
     * Get all Bekanntmachungen with optional pagination.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);

        $bekanntmachungen = Bekanntmachung::with('cpvCodes')
            ->orderBy('veroeffentlicht', 'desc')
            ->paginate($limit, ['*'], 'page', $page);

        $results = $bekanntmachungen->map(function ($item) {
            return [
                'id' => $item->id,
                'veroeffentlicht' => $item->veroeffentlicht->format('Y-m-d'),
                'angebotsfrist' => $item->angebotsfrist?->format('Y-m-d'),
                'kurzbezeichnung' => $item->kurzbezeichnung,
                'typ' => $item->typ,
                'vergabeplattform' => $item->vergabeplattform,
                'geschaetzter_auftragswert' => $item->geschaetzter_auftragswert,
                'beschreibung' => $item->beschreibung,
                'cpv_codes' => $item->cpvCodes->map(function ($cpv) {
                    return [
                        'code' => $cpv->code,
                        'title' => $cpv->title,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $results->count(),
            'total' => $bekanntmachungen->total(),
            'current_page' => $bekanntmachungen->currentPage(),
            'last_page' => $bekanntmachungen->lastPage(),
            'data' => $results,
        ]);
    }

    /**
     * Get a single Bekanntmachung by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $bekanntmachung = Bekanntmachung::with('cpvCodes')->find($id);

        if (!$bekanntmachung) {
            return response()->json([
                'success' => false,
                'message' => 'Bekanntmachung not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $bekanntmachung->id,
                'veroeffentlicht' => $bekanntmachung->veroeffentlicht->format('Y-m-d'),
                'angebotsfrist' => $bekanntmachung->angebotsfrist?->format('Y-m-d'),
                'kurzbezeichnung' => $bekanntmachung->kurzbezeichnung,
                'typ' => $bekanntmachung->typ,
                'vergabeplattform' => $bekanntmachung->vergabeplattform,
                'geschaetzter_auftragswert' => $bekanntmachung->geschaetzter_auftragswert,
                'beschreibung' => $bekanntmachung->beschreibung,
                'cpv_codes' => $bekanntmachung->cpvCodes->map(function ($cpv) {
                    return [
                        'code' => $cpv->code,
                        'title' => $cpv->title,
                    ];
                })->toArray(),
            ],
        ]);
    }
}
