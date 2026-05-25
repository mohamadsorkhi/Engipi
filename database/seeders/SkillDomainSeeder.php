<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillDomainSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate entire skill hierarchy (FK-safe order)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('skills')->truncate();
        DB::table('processes')->truncate();
        DB::table('subdomains')->truncate();
        DB::table('skill_domains')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $domains = [
            'مهندسی برق',
            'مهندسی مکانیک',
            'مهندسی عمران',
            'مهندسی معماری',
            'مهندسی شهرسازی',
            'مهندسی نقشه‌برداری',
            'مهندسی کامپیوتر',
            'مهندسی صنایع',
            'مهندسی شیمی',
            'مهندسی نفت',
            'مهندسی متالورژی و مواد',
            'مهندسی هوافضا',
            'مهندسی دریا',
            'مهندسی هسته‌ای',
            'مهندسی پزشکی (بیومدیکال)',
            'مهندسی محیط زیست',
            'مهندسی معدن',
            'مهندسی کشاورزی و منابع طبیعی',
            'مهندسی تاسیسات',
            'میان‌رشته‌ای',
        ];

        $now  = now();
        $rows = [];

        foreach ($domains as $name) {
            $rows[] = [
                'id'         => (string) Str::uuid(),
                'name'       => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('skill_domains')->insert($rows);

        $this->command->info('✓ skill_domains : ' . count($rows) . ' records');
    }
}
