<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubdomainSeeder extends Seeder
{
    public function run(): void
    {
        // Re-truncate subdomains & skills only (skill_domains must already exist)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('skills')->truncate();
        DB::table('subdomains')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // domain name => [subdomains]
        $data = [
            'مهندسی برق' => [
                'قدرت',
                'الکترونیک',
                'مخابرات',
                'کنترل',
                'ابزاردقیق',
                'الکترونیک قدرت',
                'سیستم‌های دیجیتال',
                'مهندسی پزشکی برق',
            ],
            'مهندسی مکانیک' => [
                'طراحی جامدات',
                'تبدیل انرژی',
                'سیالات',
                'ساخت و تولید',
                'ارتعاشات',
                'حرارت و سیال',
            ],
            'مهندسی عمران' => [
                'سازه',
                'ژئوتکنیک',
                'آب و هیدرولیک',
                'راه و ترابری',
                'محیط زیست',
                'زلزله',
                'مدیریت ساخت',
            ],
            'مهندسی معماری' => [
                'معماری',
                'معماری داخلی',
                'مرمت بناها',
                'معماری منظر',
            ],
            'مهندسی شهرسازی' => [
                'برنامه‌ریزی شهری',
                'برنامه‌ریزی منطقه‌ای',
                'طراحی شهری',
                'ترافیک و حمل‌ونقل',
            ],
            'مهندسی نقشه‌برداری' => [
                'نقشه‌برداری زمینی',
                'فتوگرامتری',
                'سنجش از دور',
                'GIS',
                'ژئودزی',
            ],
            'مهندسی کامپیوتر' => [
                'نرم‌افزار',
                'سخت‌افزار',
                'هوش مصنوعی',
                'امنیت',
                'شبکه',
                'داده',
            ],
            'مهندسی صنایع' => [
                'تولید',
                'لجستیک',
                'کیفیت',
                'بهینه‌سازی',
                'ایمنی صنعتی',
            ],
            'مهندسی شیمی' => [
                'فرآیند',
                'بیوشیمی',
                'پلیمر',
                'محیط زیست شیمیایی',
            ],
            'مهندسی نفت' => [
                'مخزن',
                'حفاری',
                'بهره‌برداری',
                'خطوط لوله',
            ],
            'مهندسی متالورژی و مواد' => [
                'فلزات',
                'سرامیک',
                'پلیمر',
                'نانومواد',
                'خوردگی',
            ],
            'مهندسی هوافضا' => [
                'آیرودینامیک',
                'سازه هوایی',
                'پیشرانش',
                'آویونیک',
            ],
            'مهندسی دریا' => [
                'سازه‌های دریایی',
                'کشتی‌سازی',
                'هیدرودینامیک',
            ],
            'مهندسی هسته‌ای' => [
                'راکتور',
                'پرتوپزشکی',
                'حفاظت در برابر پرتو',
            ],
            'مهندسی پزشکی (بیومدیکال)' => [
                'تجهیزات پزشکی',
                'بیوالکتریک',
                'بیومکانیک',
                'بافت',
            ],
            'مهندسی محیط زیست' => [
                'آب و فاضلاب',
                'هوا',
                'پسماند',
                'ارزیابی زیست‌محیطی',
            ],
            'مهندسی معدن' => [
                'استخراج',
                'فرآوری',
                'ژئومکانیک',
                'اکتشاف',
            ],
            'مهندسی کشاورزی و منابع طبیعی' => [
                'آبیاری',
                'ماشین‌آلات کشاورزی',
                'منابع آب',
            ],
            'مهندسی تاسیسات' => [
                'تاسیسات برقی',
                'تاسیسات مکانیکی',
                'HVAC',
            ],
            'میان‌رشته‌ای' => [
                'مکاترونیک',
                'انرژی تجدیدپذیر',
                'فناوری نانو',
                'بیوانفورماتیک',
            ],
        ];

        // Build domain name → id lookup
        $domainMap = DB::table('skill_domains')->pluck('id', 'name')->toArray();

        $now     = now();
        $count   = 0;
        $missing = [];

        foreach ($data as $domainName => $subdomains) {
            if (! isset($domainMap[$domainName])) {
                $missing[] = $domainName;
                continue;
            }

            $domainId = $domainMap[$domainName];
            $rows     = [];

            foreach ($subdomains as $sub) {
                $rows[] = [
                    'id'              => (string) Str::uuid(),
                    'name'            => $sub,
                    'skill_domain_id' => $domainId,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
                $count++;
            }

            DB::table('subdomains')->insert($rows);
        }

        $this->command->info("✓ subdomains : {$count} records");

        foreach ($missing as $m) {
            $this->command->warn("  Domain not found: {$m}");
        }
    }
}
