<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubdomainSeeder extends Seeder
{
    public function run(): void
    {
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
            'میان‌رشته‌ای' => [
                'مکاترونیک',
                'انرژی تجدیدپذیر',
                'فناوری نانو',
                'بیوانفورماتیک',
            ],
        ];

        [$created, $existing] = DB::transaction(function () use ($data): array {
            $domainMap = DB::table('skill_domains')->pluck('id', 'name')->all();
            $missing = array_values(array_diff(array_keys($data), array_keys($domainMap)));
            if ($missing !== []) { throw new \RuntimeException('SubdomainSeeder cannot continue; parent domains not found: '.implode(', ', $missing)); }
            $created = 0; $existing = 0;
            foreach ($data as $domainName => $names) {
                foreach (array_unique($names) as $name) {
                    $query = DB::table('subdomains')->where('skill_domain_id', $domainMap[$domainName])->where('name', $name);
                    if ($query->exists()) { $existing++; continue; }
                    DB::table('subdomains')->insert(['id'=>(string) Str::uuid(),'name'=>$name,'skill_domain_id'=>$domainMap[$domainName],'created_at'=>now(),'updated_at'=>now()]);
                    $created++;
                }
            }
            return [$created, $existing];
        });
        $this->command?->info("Subdomains seeded: {$created} created, {$existing} existing");
    }
}