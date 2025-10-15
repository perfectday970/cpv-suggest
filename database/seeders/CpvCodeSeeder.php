<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CpvCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('data/cpv.csv');

        // If CSV file exists, load from it
        if (File::exists($csvPath)) {
            $this->seedFromCsv($csvPath);
        } else {
            // Otherwise, seed with sample data
            $this->seedSampleData();
        }
    }

    /**
     * Seed from CSV file.
     */
    private function seedFromCsv(string $path): void
    {
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 0, ';'); // Use semicolon delimiter

        $allCodes = [];
        $seenCodes = [];

        // Collect all unique codes
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            // Skip empty rows
            if (empty($row[0]) || empty($row[1])) {
                continue;
            }

            // Extract code without the check digit (e.g., "03000000-1" -> "03000000")
            $code = preg_replace('/-\d+$/', '', trim($row[0]));
            $title = trim($row[1]);

            // Skip if code is invalid or duplicate
            if (strlen($code) !== 8 || isset($seenCodes[$code])) {
                continue;
            }

            $seenCodes[$code] = true;
            $level = $this->calculateLevel($code);

            $allCodes[] = [
                'code' => $code,
                'title' => $title,
                'level' => $level,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        fclose($handle);

        // Insert in batches (no need to sort by level anymore)
        $batch = [];
        foreach ($allCodes as $code) {
            $batch[] = $code;

            if (count($batch) >= 500) {
                DB::table('cpv_codes')->insert($batch);
                $batch = [];
            }
        }

        // Insert remaining codes
        if (!empty($batch)) {
            DB::table('cpv_codes')->insert($batch);
        }

        $this->command->info('Imported ' . count($allCodes) . ' unique CPV codes.');
    }

    /**
     * Seed with sample CPV codes for testing.
     */
    private function seedSampleData(): void
    {
        $codes = [
            // Level 1 - Divisions
            ['code' => '70000000', 'title' => 'Dienstleistungen von Architektur-, Konstruktions- und Ingenieurbüros und Prüfstellen', 'level' => 1],
            ['code' => '71000000', 'title' => 'Dienstleistungen von Architektur- und Ingenieurbüros sowie planungsbezogene Leistungen', 'level' => 1],
            ['code' => '72000000', 'title' => 'IT-Dienste: Beratung, Software-Entwicklung, Internet und Hilfestellung', 'level' => 1],
            ['code' => '73000000', 'title' => 'Forschung und Entwicklung sowie zugehörige Beratung', 'level' => 1],
            ['code' => '75000000', 'title' => 'Dienstleistungen der öffentlichen Verwaltung, Verteidigung und Sozialversicherung', 'level' => 1],
            ['code' => '79000000', 'title' => 'Dienstleistungen für Unternehmen: Recht, Marketing, Beratung, Einstellungen, Druck und Sicherheit', 'level' => 1],
            ['code' => '80000000', 'title' => 'Allgemeine und berufliche Bildung', 'level' => 1],

            // Level 2 - Groups (IT Services)
            ['code' => '72200000', 'title' => 'Softwareprogrammierung und -beratung', 'level' => 2],
            ['code' => '72300000', 'title' => 'Datenverarbeitung', 'level' => 2],
            ['code' => '72400000', 'title' => 'Internet-Dienste', 'level' => 2],
            ['code' => '72500000', 'title' => 'Computereinrichtungen', 'level' => 2],
            ['code' => '72600000', 'title' => 'Computerunterstützte Dienstleistungen', 'level' => 2],

            // Level 3 - Classes
            ['code' => '72220000', 'title' => 'Beratung im Bereich Systeme und technische Beratung', 'level' => 3],
            ['code' => '72230000', 'title' => 'Entwicklung von kundenspezifischer Software', 'level' => 3],
            ['code' => '72240000', 'title' => 'Beratung und Entwicklung von Systemen und Software', 'level' => 3],
            ['code' => '72250000', 'title' => 'System- und Unterstützungsdienstleistungen', 'level' => 3],
            ['code' => '72260000', 'title' => 'Software-Beratung und -Bereitstellung', 'level' => 3],
            ['code' => '72270000', 'title' => 'Wartung und Reparatur von Software', 'level' => 3],

            // Level 4 - Categories
            ['code' => '72221000', 'title' => 'Beratung für Geschäftsanalysen', 'level' => 4],
            ['code' => '72222000', 'title' => 'Beratung in Systemarchitektur und -Entwicklung', 'level' => 4],
            ['code' => '72223000', 'title' => 'Beratung in Systembetrieb', 'level' => 4],
            ['code' => '72224000', 'title' => 'Beratung in Projektmanagement', 'level' => 4],

            // Specific services
            ['code' => '72222300', 'title' => 'Informationstechnische Beratungsdienste', 'level' => 5],
            ['code' => '72224100', 'title' => 'Projektleitung für Bauprojekte im Bereich IT', 'level' => 5],

            // Business services
            ['code' => '79100000', 'title' => 'Rechtsberatung und -vertretung', 'level' => 2],
            ['code' => '79400000', 'title' => 'Unternehmens- und Managementberatung und zugehörige Dienste', 'level' => 2],
            ['code' => '79410000', 'title' => 'Unternehmens- und Managementberatung', 'level' => 3],
            ['code' => '79411000', 'title' => 'Allgemeine Managementberatung', 'level' => 4],
            ['code' => '79421000', 'title' => 'Projektmanagement außer Bauarbeiten', 'level' => 4],

            // Construction
            ['code' => '45000000', 'title' => 'Bauarbeiten', 'level' => 1],
            ['code' => '45200000', 'title' => 'Bauarbeiten für Gebäude', 'level' => 2],
            ['code' => '45300000', 'title' => 'Bauinstallationsarbeiten', 'level' => 2],

            // Healthcare
            ['code' => '85000000', 'title' => 'Gesundheits- und Sozialwesen', 'level' => 1],
            ['code' => '85100000', 'title' => 'Dienstleistungen des Gesundheitswesens', 'level' => 2],
        ];

        foreach ($codes as &$code) {
            $code['created_at'] = now();
            $code['updated_at'] = now();
        }

        DB::table('cpv_codes')->insert($codes);

        $this->command->info('Seeded ' . count($codes) . ' sample CPV codes.');
    }

    /**
     * Calculate level based on code format.
     *
     * CPV codes use a hierarchical structure where the level is determined
     * by the number of significant digits (excluding trailing zeros):
     * - Level 1: 2 digits  (XX000000) e.g., 72000000
     * - Level 2: 3-4 digits (XXYY0000) e.g., 72200000, 72210000
     * - Level 3: 5-6 digits (XXYYZZ00) e.g., 72220000, 72222000
     * - Level 4: 7-8 digits (XXYYZZKK) e.g., 72222200, 72222300
     */
    private function calculateLevel(string $code): int
    {
        // Remove trailing zeros
        $trimmed = rtrim($code, '0');
        $length = strlen($trimmed);

        // Determine level based on significant digit count
        if ($length <= 2) {
            return 1;
        } elseif ($length <= 4) {
            return 2;
        } elseif ($length <= 6) {
            return 3;
        } else {
            return 4;
        }
    }

}
