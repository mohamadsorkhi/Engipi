<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('skill_domains')->insert([
            [
                'id'=>Str::uuid(),
                'name'=>'مهندسی عمران',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

            [
                'id'=>Str::uuid(),
                'name'=>'مهندسی برق',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

            [
                'id'=>Str::uuid(),
                'name'=>'مهندسی نقشه‌برداری',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
        ]);
    }
}