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
        $header = fgetcsv($handle);

        $codes = [];
        while (($row = fgetcsv($handle)) !== false) {
            $codes[] = [
                'code' => $row[0],
                'title' => $row[1],
                'level' => $row[2] ?? $this->calculateLevel($row[0]),
                'parent_code' => $row[3] ?? $this->getParentCode($row[0]),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert in batches of 500
            if (count($codes) >= 500) {
                DB::table('cpv_codes')->insert($codes);
                $codes = [];
            }
        }

        // Insert remaining codes
        if (!empty($codes)) {
            DB::table('cpv_codes')->insert($codes);
        }

        fclose($handle);
    }

    /**
     * Seed with sample CPV codes for testing.
     */
    private function seedSampleData(): void
    {
        $codes = [
            // Level 1 - Divisions
            ['code' => '70000000', 'title' => 'Dienstleistungen von Architektur-, Konstruktions- und Ingenieurbüros und Prüfstellen', 'level' => 1, 'parent_code' => null],
            ['code' => '71000000', 'title' => 'Dienstleistungen von Architektur- und Ingenieurbüros sowie planungsbezogene Leistungen', 'level' => 1, 'parent_code' => null],
            ['code' => '72000000', 'title' => 'IT-Dienste: Beratung, Software-Entwicklung, Internet und Hilfestellung', 'level' => 1, 'parent_code' => null],
            ['code' => '73000000', 'title' => 'Forschung und Entwicklung sowie zugehörige Beratung', 'level' => 1, 'parent_code' => null],
            ['code' => '75000000', 'title' => 'Dienstleistungen der öffentlichen Verwaltung, Verteidigung und Sozialversicherung', 'level' => 1, 'parent_code' => null],
            ['code' => '79000000', 'title' => 'Dienstleistungen für Unternehmen: Recht, Marketing, Beratung, Einstellungen, Druck und Sicherheit', 'level' => 1, 'parent_code' => null],
            ['code' => '80000000', 'title' => 'Allgemeine und berufliche Bildung', 'level' => 1, 'parent_code' => null],

            // Level 2 - Groups (IT Services)
            ['code' => '72200000', 'title' => 'Softwareprogrammierung und -beratung', 'level' => 2, 'parent_code' => '72000000'],
            ['code' => '72300000', 'title' => 'Datenverarbeitung', 'level' => 2, 'parent_code' => '72000000'],
            ['code' => '72400000', 'title' => 'Internet-Dienste', 'level' => 2, 'parent_code' => '72000000'],
            ['code' => '72500000', 'title' => 'Computereinrichtungen', 'level' => 2, 'parent_code' => '72000000'],
            ['code' => '72600000', 'title' => 'Computerunterstützte Dienstleistungen', 'level' => 2, 'parent_code' => '72000000'],

            // Level 3 - Classes
            ['code' => '72220000', 'title' => 'Beratung im Bereich Systeme und technische Beratung', 'level' => 3, 'parent_code' => '72200000'],
            ['code' => '72230000', 'title' => 'Entwicklung von kundenspezifischer Software', 'level' => 3, 'parent_code' => '72200000'],
            ['code' => '72240000', 'title' => 'Beratung und Entwicklung von Systemen und Software', 'level' => 3, 'parent_code' => '72200000'],
            ['code' => '72250000', 'title' => 'System- und Unterstützungsdienstleistungen', 'level' => 3, 'parent_code' => '72200000'],
            ['code' => '72260000', 'title' => 'Software-Beratung und -Bereitstellung', 'level' => 3, 'parent_code' => '72200000'],
            ['code' => '72270000', 'title' => 'Wartung und Reparatur von Software', 'level' => 3, 'parent_code' => '72200000'],

            // Level 4 - Categories
            ['code' => '72221000', 'title' => 'Beratung für Geschäftsanalysen', 'level' => 4, 'parent_code' => '72220000'],
            ['code' => '72222000', 'title' => 'Beratung in Systemarchitektur und -Entwicklung', 'level' => 4, 'parent_code' => '72220000'],
            ['code' => '72223000', 'title' => 'Beratung in Systembetrieb', 'level' => 4, 'parent_code' => '72220000'],
            ['code' => '72224000', 'title' => 'Beratung in Projektmanagement', 'level' => 4, 'parent_code' => '72220000'],

            // Specific services
            ['code' => '72222300', 'title' => 'Informationstechnische Beratungsdienste', 'level' => 5, 'parent_code' => '72222000'],
            ['code' => '72224100', 'title' => 'Projektleitung für Bauprojekte im Bereich IT', 'level' => 5, 'parent_code' => '72224000'],

            // Business services
            ['code' => '79100000', 'title' => 'Rechtsberatung und -vertretung', 'level' => 2, 'parent_code' => '79000000'],
            ['code' => '79400000', 'title' => 'Unternehmens- und Managementberatung und zugehörige Dienste', 'level' => 2, 'parent_code' => '79000000'],
            ['code' => '79410000', 'title' => 'Unternehmens- und Managementberatung', 'level' => 3, 'parent_code' => '79400000'],
            ['code' => '79411000', 'title' => 'Allgemeine Managementberatung', 'level' => 4, 'parent_code' => '79410000'],
            ['code' => '79421000', 'title' => 'Projektmanagement außer Bauarbeiten', 'level' => 4, 'parent_code' => '79410000'],

            // Construction
            ['code' => '45000000', 'title' => 'Bauarbeiten', 'level' => 1, 'parent_code' => null],
            ['code' => '45200000', 'title' => 'Bauarbeiten für Gebäude', 'level' => 2, 'parent_code' => '45000000'],
            ['code' => '45300000', 'title' => 'Bauinstallationsarbeiten', 'level' => 2, 'parent_code' => '45000000'],

            // Healthcare
            ['code' => '85000000', 'title' => 'Gesundheits- und Sozialwesen', 'level' => 1, 'parent_code' => null],
            ['code' => '85100000', 'title' => 'Dienstleistungen des Gesundheitswesens', 'level' => 2, 'parent_code' => '85000000'],
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
     */
    private function calculateLevel(string $code): int
    {
        $code = rtrim($code, '0');
        return (int) ceil(strlen($code) / 2);
    }

    /**
     * Get parent code based on hierarchy.
     */
    private function getParentCode(string $code): ?string
    {
        if (strlen($code) <= 2) {
            return null;
        }

        // Remove trailing zeros and last 2 digits, then pad with zeros
        $parent = substr($code, 0, -2);
        return str_pad($parent, 8, '0');
    }
}
