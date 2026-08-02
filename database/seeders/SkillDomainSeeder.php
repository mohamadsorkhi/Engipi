<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillDomainSeeder extends Seeder
{
    public function run(): void
    {
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
            'مهندسی پزشکی (بیومدیکال)',
            'مهندسی محیط زیست',
            'مهندسی معدن',
            'میان‌رشته‌ای',
        ];

        [$created, $existing] = DB::transaction(function () use ($domains): array {
            $created = 0;
            $existing = 0;
            foreach ($domains as $name) {
                if (DB::table('skill_domains')->where('name', $name)->exists()) { $existing++; continue; }
                DB::table('skill_domains')->insert(['id'=>(string) Str::uuid(),'name'=>$name,'created_at'=>now(),'updated_at'=>now()]);
                $created++;
            }
            return [$created, $existing];
        });
        $this->command?->info("Skill domains seeded: {$created} created, {$existing} existing");
    }
}