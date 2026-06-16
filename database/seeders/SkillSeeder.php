<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('skill_subdomain')->truncate();
        DB::table('skills')->truncate();
        DB::table('processes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $now = now();

        // domain => subdomain => [tools]
        $data = [

            'مهندسی برق' => [
                'قدرت' => [
                    'ETAP', 'DIgSILENT PowerFactory', 'PSS/E', 'PSCAD',
                    'MATLAB/Simulink', 'AutoCAD Electrical', 'EPLAN Electric P8',
                ],
                'الکترونیک' => [
                    'Altium Designer', 'KiCad', 'Eagle CAD', 'Cadence OrCAD',
                    'LTspice', 'Proteus', 'Multisim', 'VHDL', 'Verilog',
                    'Quartus', 'Vivado',
                ],
                'مخابرات' => [
                    'MATLAB Communications Toolbox', 'GNU Radio', 'ADS Keysight',
                    'HFSS', 'CST Studio', 'NS-3',
                ],
                'کنترل' => [
                    'MATLAB/Simulink', 'TIA Portal', 'RSLogix 5000', 'CodeSys',
                    'WinCC', 'Ignition SCADA', 'LabVIEW', 'ROS', 'PLC Programming',
                ],
                'الکترونیک قدرت' => [
                    'PSIM', 'PLECS', 'LTspice', 'MATLAB/Simulink',
                    'PSCAD', 'SIMetrix', 'GeckoCIRCUITS',
                ],
                'مهندسی پزشکی برق' => [
                    'MATLAB Biomedical Toolbox', 'LabVIEW', 'EEGLAB',
                    'BrainVision Analyzer', 'BIOPAC Student Lab', 'SPM',
                ],
                'ابزاردقیق' => [
                    'LabVIEW', 'MATLAB', 'Python', 'Modbus', 'OPC-UA',
                    'Ignition SCADA', 'WinCC',
                ],
                'سیستم‌های دیجیتال' => [
                    'VHDL', 'Verilog', 'SystemVerilog', 'Quartus Prime',
                    'Vivado', 'ModelSim', 'FPGA Design',
                ],
            ],

            'مهندسی مکانیک' => [
                'طراحی جامدات' => [
                    'SOLIDWORKS', 'CATIA', 'PTC Creo', 'Siemens NX',
                    'Autodesk Inventor', 'Fusion 360', 'AutoCAD Mechanical',
                    'ANSYS Mechanical', 'Abaqus', 'MSC Nastran', 'LS-DYNA',
                ],
                'سیالات' => [
                    'ANSYS Fluent', 'OpenFOAM', 'STAR-CCM+',
                ],
                'تبدیل انرژی' => [
                    'COMSOL Multiphysics', 'EES', 'MATLAB',
                    'GT-Suite', 'ANSYS CFX', 'Cycle-Tempo',
                ],
                'ارتعاشات' => [
                    'Adams', 'MATLAB', 'ANSYS Mechanical',
                    'Simcenter Testlab', 'Siemens NX CAE', "ME'scope VES",
                ],
                'حرارت و سیال' => [
                    'ANSYS Fluent', 'OpenFOAM', 'COMSOL Multiphysics',
                    'STAR-CCM+', 'MATLAB', 'EES',
                ],
                'ساخت و تولید' => [
                    'Mastercam', 'SolidWorks CAM', 'PowerMill',
                    'CATIA Manufacturing', 'Siemens NX CAM', 'MATLAB',
                ],
            ],

            'مهندسی عمران' => [
                'سازه' => [
                    'ETABS', 'SAP2000', 'SAFE', 'Tekla Structures',
                    'Revit Structure', 'ROBOT Structural', 'STAAD.Pro', 'PERFORM-3D',
                ],
                'ژئوتکنیک' => [
                    'PLAXIS 2D/3D', 'GeoStudio', 'FLAC', 'RS2/RS3 Rocscience',
                    'AutoCAD Civil 3D',
                ],
                'آب و هیدرولیک' => [
                    'HEC-RAS', 'HEC-HMS', 'EPANET', 'WaterGEMS',
                    'SWMM', 'MIKE FLOOD', 'MODFLOW',
                ],
                'راه و ترابری' => [
                    'AutoCAD Civil 3D', 'OpenRoads', 'VISSIM (ترافیک)', 'AIMSUN', 'TransModeler',
                ],
                'زلزله' => [
                    'SeismoSoft', 'OpenSees', 'PERFORM-3D',
                    'SAP2000 Nonlinear', 'ETABS Nonlinear', 'SeismoStruct',
                ],
                'محیط زیست' => [
                    'ArcGIS', 'SWMM', 'WASP', 'QUAL2K', 'HEC-RAS',
                ],
                'مدیریت ساخت' => [
                    'Primavera P6', 'MS Project', 'BIM 360',
                    'Revit', 'Navisworks', 'Synchro 4D',
                ],
            ],

            'مهندسی معماری' => [
                'معماری' => [
                    'Revit', 'ArchiCAD', 'SketchUp', 'Rhino 3D',
                    'Grasshopper', 'Lumion', 'V-Ray', 'AutoCAD', '3ds Max',
                ],
                'معماری داخلی' => [
                    'AutoCAD', 'SketchUp', '3ds Max', 'V-Ray',
                    'Revit', 'Enscape', 'Lumion',
                ],
                'مرمت بناها' => [
                    'AutoCAD', 'Revit', 'ArcGIS', 'SketchUp',
                    '3D Scanning Tools', 'CloudCompare',
                ],
                'معماری منظر' => [
                    'AutoCAD', 'SketchUp', 'Rhino 3D', 'Lumion',
                    'Land F/X', 'ArcGIS',
                ],
            ],

            'مهندسی شهرسازی' => [
                'برنامه‌ریزی شهری' => [
                    'ArcGIS', 'QGIS', 'AutoCAD', 'SketchUp',
                    'Revit', 'CityEngine', 'Space Syntax', 'VISSIM',
                ],
                'برنامه‌ریزی منطقه‌ای' => [
                    'ArcGIS', 'QGIS', 'CommunityViz', 'INDEX',
                    'AutoCAD', 'UrbanSim',
                ],
                'طراحی شهری' => [
                    'SketchUp', 'AutoCAD', 'Rhino 3D', 'Grasshopper',
                    'Lumion', 'CityEngine', 'Revit',
                ],
                'ترافیک و حمل‌ونقل' => [
                    'VISSIM', 'TransCAD', 'AIMSUN', 'SATURN',
                    'VISUM', 'Synchro',
                ],
            ],

            'مهندسی نقشه‌برداری' => [
                'GIS' => [
                    'ArcGIS Pro', 'QGIS', 'Global Mapper',
                    'GRASS GIS', 'MapInfo Pro', 'Google Earth Pro',
                ],
                'فتوگرامتری' => [
                    'Pix4D', 'Agisoft Metashape',
                    'RealityCapture', '3DF Zephyr', 'DJI Terra',
                ],
                'نقشه‌برداری زمینی' => [
                    'AutoCAD Civil 3D', 'Leica Infinity', 'Trimble',
                ],
                'سنجش از دور' => [
                    'ENVI', 'ERDAS Imagine', 'Google Earth Engine',
                    'ArcGIS', 'SNAP (ESA)', 'QGIS',
                ],
                'ژئودزی' => [
                    'MATLAB', 'StarNet', 'GNU Gama',
                    'Bernese GNSS Software', 'Leica Infinity',
                ],
            ],

            'مهندسی کامپیوتر' => [
                'نرم‌افزار' => [
                    'Python', 'Java', 'C++', 'JavaScript',
                    'Laravel', 'Django', 'React', 'Node.js', 'Docker', 'Git',
                ],
                'هوش مصنوعی' => [
                    'TensorFlow', 'PyTorch', 'Scikit-learn', 'Hugging Face',
                    'OpenCV', 'CUDA', 'MLflow',
                ],
                'شبکه' => [
                    'Cisco Packet Tracer', 'GNS3', 'Wireshark',
                    'Ansible', 'Terraform', 'Kubernetes',
                ],
                'داده' => [
                    'Python/Pandas', 'SQL', 'Apache Spark',
                    'Power BI', 'Tableau', 'dbt', 'Airflow',
                ],
                'سخت‌افزار' => [
                    'Logisim', 'gem5', 'QEMU',
                    'ModelSim', 'Cadence Virtuoso', 'LLVM',
                    'Xilinx Vitis HLS',
                ],
                'امنیت' => [
                    'Wireshark', 'Metasploit', 'Burp Suite',
                    'Nmap', 'Python (Scapy)', 'Kali Linux',
                ],
            ],

            'مهندسی صنایع' => [
                'تولید' => [
                    'Arena', 'AnyLogic', 'Minitab', 'LINGO',
                    'SAP ERP', 'MATLAB', 'AutoCAD Plant 3D', 'Lean/VSM',
                ],
                'لجستیک' => [
                    'AnyLogic', 'FlexSim', 'SAP SCM',
                    'MATLAB', 'Python', 'Arena',
                ],
                'کیفیت' => [
                    'Minitab', 'JMP', 'SPSS',
                    'MATLAB', 'SPC Tools', 'R',
                ],
                'بهینه‌سازی' => [
                    'LINGO', 'GAMS', 'CPLEX', 'Gurobi',
                    'Python (SciPy)', 'MATLAB',
                ],
                'ایمنی صنعتی' => [
                    'BowTieXP', 'PHA-Pro', 'PHAST',
                    'SAFETI', 'HAZOP Tools', 'RiskSpectrum',
                ],
            ],

            'مهندسی شیمی' => [
                'فرآیند' => [
                    'Aspen Plus', 'HYSYS', 'CHEMCAD', 'HTRI',
                    'AutoCAD P&ID', 'SmartPlant',
                ],
                'بیوشیمی' => [
                    'MATLAB', 'Aspen Plus', 'SuperPro Designer',
                    'Python (BioPython)', 'R', 'COMSOL',
                ],
                'پلیمر' => [
                    'Aspen Polymers', 'COMSOL', 'Moldflow',
                    'Polyflow', 'ABAQUS', 'MATLAB',
                ],
                'محیط زیست شیمیایی' => [
                    'Aspen Plus', 'HSC Chemistry', 'OLI Studio',
                    'MATLAB', 'Python', 'ArcGIS',
                ],
            ],

            'مهندسی نفت' => [
                'مخزن' => [
                    'Petrel', 'Eclipse', 'PIPESIM', 'HYSYS',
                    'Aspen Plus', 'WellPlan',
                ],
                'حفاری' => [
                    'WellPlan', 'COMPASS', 'DrillBench',
                    'Petrel', 'MATLAB', 'StressCheck',
                ],
                'بهره‌برداری' => [
                    'PROSPER', 'GAP', 'PIPESIM',
                    'HYSYS', 'OFM', 'Petrel',
                ],
                'خطوط لوله' => [
                    'OLGA', 'PIPESIM', 'SPS',
                    'Caesar II', 'AutoCAD Plant 3D', 'Synergi Pipeline',
                ],
            ],

            'مهندسی متالورژی و مواد' => [
                'فلزات' => [
                    'MATLAB', 'ANSYS', 'Thermo-Calc', 'JMatPro', 'ABAQUS',
                ],
                'سرامیک' => [
                    'COMSOL', 'Thermo-Calc', 'MATLAB',
                    'ANSYS', 'OriginPro', 'JMatPro',
                ],
                'پلیمر' => [
                    'COMSOL Multiphysics', 'Thermo-Calc', 'ABAQUS',
                    'OriginPro', 'MATLAB', 'Python',
                ],
                'خوردگی' => [
                    'COMSOL', 'HSC Chemistry', 'OLI Studio',
                    'MATLAB', 'Python', 'OriginPro',
                ],
                'نانومواد' => [
                    'COMSOL', 'LAMMPS', 'VASP',
                    'Quantum ESPRESSO', 'MATLAB', 'Python',
                ],
            ],

            'مهندسی هوافضا' => [
                'آیرودینامیک' => [
                    'CATIA', 'ANSYS CFD', 'OpenVSP', 'XFLR5',
                    'STK', 'MATLAB Aerospace Toolbox',
                ],
                'سازه هوایی' => [
                    'MSC Nastran', 'ANSYS Mechanical', 'Abaqus',
                    'Patran', 'CATIA', 'HyperMesh',
                ],
                'پیشرانش' => [
                    'MATLAB', 'CEA (NASA)', 'Cantera',
                    'ANSYS CFX', 'RocketCEA', 'OpenFOAM',
                ],
                'آویونیک' => [
                    'MATLAB/Simulink', 'LabVIEW', 'Python',
                    'VHDL', 'STK', 'dSPACE',
                ],
            ],

            'مهندسی پزشکی (بیومدیکال)' => [
                'تجهیزات پزشکی' => [
                    'MATLAB Biomedical', '3D Slicer', 'COMSOL',
                    'SolidWorks Medical', 'LabVIEW', 'DICOM',
                ],
                'بیوالکتریک' => [
                    'MATLAB', 'EEGLAB', 'LabVIEW',
                    'BIOPAC', 'SPM', 'COMSOL',
                ],
                'بیومکانیک' => [
                    'ANSYS Mechanical', 'Abaqus', 'MIMICS',
                    '3-matic', 'OpenSim', 'AnyBody',
                ],
                'بافت' => [
                    'COMSOL Multiphysics', 'MATLAB', 'ImageJ',
                    'ANSYS Fluent', 'Abaqus', 'Python',
                ],
            ],

            'مهندسی محیط زیست' => [
                'آب و فاضلاب' => [
                    'ArcGIS', 'MATLAB', 'EPANET', 'HEC-RAS', 'WASP',
                ],
                'هوا' => [
                    'AERMOD', 'CALPUFF', 'MATLAB',
                    'Python', 'ArcGIS',
                ],
                'پسماند' => [
                    'MATLAB', 'ArcGIS', 'Python',
                    'AERMOD', 'SimaPro', 'OpenLCA',
                ],
                'ارزیابی زیست‌محیطی' => [
                    'ArcGIS', 'QGIS', 'SimaPro',
                    'OpenLCA', 'MATLAB', 'Python',
                ],
            ],

            'مهندسی معدن' => [
                'استخراج' => [
                    'Surpac', 'Vulcan', 'Minesight', 'Datamine', 'FLAC', 'Leapfrog',
                ],
                'فرآوری' => [
                    'JKSimMet', 'USIM PAC', 'METSIM',
                    'MATLAB', 'Aspen Plus', 'Python',
                ],
                'ژئومکانیک' => [
                    'FLAC', 'PLAXIS', 'RS2/RS3',
                    'UDEC', '3DEC', 'Phase2',
                ],
                'اکتشاف' => [
                    'Leapfrog', 'ArcGIS', 'Geosoft Oasis Montaj',
                    'Petrel', 'MinVis', 'MATLAB',
                ],
            ],

            'مهندسی تاسیسات' => [
                'تاسیسات مکانیکی' => [
                    'AutoCAD MEP', 'Revit MEP', 'HAP (HVAC)', 'EnergyPlus',
                ],
                'تاسیسات برقی' => [
                    'DIALux', 'AutoCAD Electrical', 'EPLAN Electric P8',
                    'Revit MEP', 'ETAP', 'Ecodial',
                ],
                'HVAC' => [
                    'Carrier HAP', 'EnergyPlus', 'TRACE 700',
                    'IES-VE', 'eQUEST', 'Revit MEP', 'OpenStudio',
                ],
            ],

            'مهندسی دریا' => [
                'کشتی‌سازی' => [
                    'CATIA', 'Rhinoceros', 'NAPA',
                    'ShipConstructor', 'AVEVA Marine', 'AutoCAD',
                ],
                'هیدرودینامیک' => [
                    'ANSYS Fluent', 'OpenFOAM', 'WAMIT',
                    'ORCA3D', 'MAXSURF', 'Star-CCM+',
                ],
                'سازه‌های دریایی' => [
                    'ANSYS AQWA', 'OrcaFlex', 'SACS',
                    'ANSYS Mechanical', 'Abaqus', 'StruCad',
                ],
            ],

            'مهندسی هسته‌ای' => [
                'راکتور' => [
                    'MCNP', 'RELAP', 'TRACE',
                    'PARCS', 'HELIOS', 'SERPENT',
                ],
                'پرتوپزشکی' => [
                    'MATLAB', '3D Slicer', 'DICOM Tools',
                    'ISODOSE', 'Pinnacle', 'Eclipse TPS',
                ],
                'حفاظت در برابر پرتو' => [
                    'MCNP', 'FLUKA', 'ORIGEN',
                    'SCALE', 'EGSnrc', 'MATLAB',
                ],
            ],

            'مهندسی کشاورزی و منابع طبیعی' => [
                'آبیاری' => [
                    'CROPWAT', 'WaterCAD', 'ArcGIS',
                    'MATLAB', 'AutoCAD',
                ],
                'ماشین‌آلات کشاورزی' => [
                    'AutoCAD', 'EDEM', 'RecurDyn',
                    'MATLAB', 'LabVIEW', 'Python',
                ],
                'منابع آب' => [
                    'SWAT', 'HEC-HMS', 'HYDRUS',
                    'SoilVision', 'ArcGIS', 'MATLAB', 'ENVI',
                ],
            ],

            'میان‌رشته‌ای' => [
                'مکاترونیک' => [
                    'MATLAB/Simulink', 'ROS', 'Arduino',
                    'Raspberry Pi', 'SolidWorks', 'LabVIEW',
                ],
                'انرژی تجدیدپذیر' => [
                    'HOMER', 'PVsyst', 'RETScreen',
                    'MATLAB', 'OpenFOAM', 'SAM (NREL)',
                ],
                'فناوری نانو' => [
                    'COMSOL', 'LAMMPS', 'VASP',
                    'Gaussian', 'MATLAB', 'Quantum ESPRESSO',
                ],
                'بیوانفورماتیک' => [
                    'Python (Biopython)', 'R', 'BLAST',
                    'MATLAB', 'Galaxy', 'Bioconductor',
                ],
            ],

        ];

        // Build lookups
        $domainMap = DB::table('skill_domains')->pluck('id', 'name')->toArray();

        $subdomainRows = DB::table('subdomains')->get(['id', 'name', 'skill_domain_id']);
        $subLookup = [];
        foreach ($subdomainRows as $row) {
            $subLookup[$row->skill_domain_id . '|' . $row->name] = $row->id;
        }

        // 1. PROCESSES — one row per unique tool per domain
        $processCount  = 0;
        $processLookup = []; // "domain_id|tool" => process_id

        foreach ($data as $domainName => $subdomains) {
            if (! isset($domainMap[$domainName])) {
                continue;
            }
            $domainId  = $domainMap[$domainName];
            $toolsSeen = [];
            $rows      = [];

            foreach ($subdomains as $tools) {
                foreach ($tools as $tool) {
                    if (isset($toolsSeen[$tool])) {
                        continue;
                    }
                    $toolsSeen[$tool] = true;
                    $pid = (string) Str::uuid();
                    $rows[] = [
                        'id'              => $pid,
                        'name'            => $tool,
                        'skill_domain_id' => $domainId,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                    $processLookup["{$domainId}|{$tool}"] = $pid;
                    $processCount++;
                }
            }

            if ($rows) {
                DB::table('processes')->insert($rows);
            }
        }

        // 2. SKILLS — one row per tool per subdomain, linked to process
        $skillCount  = 0;
        $missingSubs = [];

        foreach ($data as $domainName => $subdomains) {
            if (! isset($domainMap[$domainName])) {
                $this->command->warn("  Domain not found: {$domainName}");
                continue;
            }
            $domainId = $domainMap[$domainName];

            foreach ($subdomains as $subName => $tools) {
                $subKey = "{$domainId}|{$subName}";
                $subId  = $subLookup[$subKey] ?? null;

                if (! $subId) {
                    $missingSubs[] = "{$domainName} > {$subName}";
                    continue;
                }

                $rows = [];
                foreach (array_unique($tools) as $tool) {
                    $rows[] = [
                        'id'           => (string) Str::uuid(),
                        'name'         => $tool,
                        'subdomain_id' => $subId,
                        'process_id'   => $processLookup["{$domainId}|{$tool}"] ?? null,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                    $skillCount++;
                }
                DB::table('skills')->insert($rows);
            }
        }

        $this->command->info("✓ processes : {$processCount} records");
        $this->command->info("✓ skills    : {$skillCount} records");

        foreach ($missingSubs as $s) {
            $this->command->warn("  Subdomain not found: {$s}");
        }
    }
}
