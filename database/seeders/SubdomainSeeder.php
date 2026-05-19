<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubdomainSeeder extends Seeder
{
    public function run(): void
    {
        $domains = [

            'مهندسی عمران' => [
                'سازه',
                'ژئوتکنیک',
                'راه و ترابری',
                'آب و فاضلاب',
                'مدیریت ساخت',
                'BIM',
                'متره و برآورد',
            ],

            'مهندسی برق' => [
                'قدرت',
                'کنترل',
                'الکترونیک',
                'مخابرات',
                'اتوماسیون صنعتی',
                'روشنایی',
            ],

            'مهندسی نقشه‌برداری' => [
                'GIS',
                'فتوگرامتری',
                'ژئودزی',
                'کاداستر',
                'هیدرولوژی',
                'توپوگرافی',
            ],

        ];

        foreach ($domains as $domainName => $subdomains)
        {
            $domain = DB::table('skill_domains')
                ->whereRaw(
                    "REPLACE(name, CHAR(8204), '') = ?",
                    [str_replace("\u{200C}", '', trim($domainName))]
                )
                ->first();

            if (!$domain) {
                dump("DOMAIN NOT FOUND: " . $domainName);
                continue;
            }

            foreach ($subdomains as $subdomain)
            {
                DB::table('subdomains')->updateOrInsert(

                    [
                        'name' => trim($subdomain),
                        'skill_domain_id' => $domain->id,
                    ],

                    [
                        'id' => (string) Str::uuid(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}