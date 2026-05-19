<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillsDataSeeder extends Seeder
{
    public function run(): void
    {
        $skillsBySubdomain = [
            'توپوگرافی' => [
    'Civil3D',
    'AutoCAD',
    'Total Station',
    'GPS'
],

'مدیریت ساخت' => [
    'Primavera P6',
    'MS Project',
    'Excel'
],

'ژئودزی' => [
    'GPS',
    'ArcGIS',
    'MATLAB'
],

'کاداستر' => [
    'ArcGIS',
    'AutoCAD',
    'GIS'
],

            'سازه' => [
                'ETABS',
                'SAFE',
                'SAP2000',
                'Tekla Structures',
                'Revit Structure'
            ],

            'قدرت' => [
                'DIgSILENT',
                'MATLAB',
                'ETAP',
                'PSCAD'
            ],

            'کنترل' => [
                'PLC',
                'MATLAB Simulink',
                'Control Systems'
            ],

            'GIS' => [
                'ArcGIS',
                'QGIS',
                'Google Earth'
            ],

            'فتوگرامتری' => [
                'Pix4D',
                'Agisoft Metashape'
            ],

           'متره و برآورد' => [
    'Excel',
    'AutoCAD',
    'تکسا',
    'صورت وضعیت'
],

           'راه و ترابری' => [
    'Civil3D',
    'Infraworks',
    'AutoCAD'
],

            'آب و فاضلاب' => [
                'EPANET',
                'WaterGEMS'
            ],

            'روشنایی' => [
                'Dialux',
                'Relux'
            ],

            'الکترونیک' => [
                'Proteus',
                'Altium Designer'
            ],

            'مخابرات' => [
                'HFSS',
                'MATLAB'
            ],

            'اتوماسیون صنعتی' => [
                'WinCC',
                'TIA Portal'
            ],

            'هیدرولوژی' => [
                'HEC-RAS',
                'HEC-HMS',
                'SWMM'
            ],

            'ژئوتکنیک' => [
                'PLAXIS',
                'GeoStudio'
            ],

            'BIM' => [
                'Revit',
                'Navisworks',
                'BIM 360'
            ]

        ];

        foreach ($skillsBySubdomain as $subName => $skills)
        {
            $cleanSubName = trim(
                str_replace(
                    ["\u{200C}", "‌"],
                    '',
                    $subName
                )
            );

            $subdomain = DB::table('subdomains')
                ->get()
                ->first(function ($item) use ($cleanSubName) {

                    $dbName = trim(
                        str_replace(
                            ["\u{200C}", "‌"],
                            '',
                            $item->name
                        )
                    );

                    return $dbName === $cleanSubName;
                });

            if (!$subdomain)
            {
                dump("NOT FOUND: ".$subName);
                continue;
            }

            foreach ($skills as $skill)
            {
                DB::table('skills')->updateOrInsert(
                    [
                        'name' => trim($skill),
                        'subdomain_id' => $subdomain->id
                    ],
                    [
                        'id' => (string) Str::uuid(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}