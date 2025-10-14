<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CpvSynonymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $synonyms = [
            // IT Services synonyms
            ['term' => 'IT-Beratung', 'code' => '72222300', 'weight' => 1.0],
            ['term' => 'IT consulting', 'code' => '72222300', 'weight' => 1.0],
            ['term' => 'Softwareentwicklung', 'code' => '72230000', 'weight' => 1.0],
            ['term' => 'Software development', 'code' => '72230000', 'weight' => 1.0],
            ['term' => 'Cloud-Migration', 'code' => '72222300', 'weight' => 0.9],
            ['term' => 'Cloud migration', 'code' => '72222300', 'weight' => 0.9],
            ['term' => 'Systemarchitektur', 'code' => '72222000', 'weight' => 1.0],
            ['term' => 'System architecture', 'code' => '72222000', 'weight' => 1.0],
            ['term' => 'Projektmanagement IT', 'code' => '72224000', 'weight' => 1.0],
            ['term' => 'IT project management', 'code' => '72224000', 'weight' => 1.0],

            // Business services synonyms
            ['term' => 'Unternehmensberatung', 'code' => '79411000', 'weight' => 1.0],
            ['term' => 'Management consulting', 'code' => '79411000', 'weight' => 1.0],
            ['term' => 'Strategieberatung', 'code' => '79411000', 'weight' => 0.9],
            ['term' => 'Strategy consulting', 'code' => '79411000', 'weight' => 0.9],
            ['term' => 'Prozessoptimierung', 'code' => '79411000', 'weight' => 0.8],
            ['term' => 'Process optimization', 'code' => '79411000', 'weight' => 0.8],

            // Construction synonyms
            ['term' => 'Bauarbeiten', 'code' => '45000000', 'weight' => 1.0],
            ['term' => 'Construction', 'code' => '45000000', 'weight' => 1.0],
            ['term' => 'Hochbau', 'code' => '45200000', 'weight' => 1.0],
            ['term' => 'Building construction', 'code' => '45200000', 'weight' => 1.0],

            // Healthcare synonyms
            ['term' => 'Gesundheitswesen', 'code' => '85100000', 'weight' => 1.0],
            ['term' => 'Healthcare', 'code' => '85100000', 'weight' => 1.0],
            ['term' => 'Medizinische Dienstleistungen', 'code' => '85100000', 'weight' => 0.9],
            ['term' => 'Medical services', 'code' => '85100000', 'weight' => 0.9],
        ];

        foreach ($synonyms as &$synonym) {
            $synonym['created_at'] = now();
            $synonym['updated_at'] = now();
        }

        DB::table('cpv_synonyms')->insert($synonyms);

        $this->command->info('Seeded ' . count($synonyms) . ' CPV synonyms.');
    }
}
