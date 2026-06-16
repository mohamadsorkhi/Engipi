<?php

// config/velzon.php

return [
    'direction' => env('THEME_DIRECTION', 'rtl'), // 'ltr' or 'rtl'
    'data_layout' => env('THEME_DATA_LAYOUT', 'vertical'), // "vertical", "horizontal", "twocolumn", "semibox"
    'data_layout_style' => env('THEME_DATA_LAYOUT_STYLE', 'default'), // "default", "detached"
    'data_sidebar_size' => env('THEME_DATA_SIDEBAR_SIZE', 'lg'), // "lg", "md", "sm", "sm-hover"
    'data_layout_width' => env('THEME_DATA_LAYOUT_WIDTH', 'fluid'), // "fluid", "boxed"
    'data_layout_position' => env('THEME_DATA_LAYOUT_POSITION', 'fixed'), // "fixed", "scrollable"
    'data_topbar' => env('THEME_DATA_TOPBAR', 'light'), // "light", "dark"
    'data_sidebar' => env('THEME_DATA_SIDEBAR', 'dark'), // "light", "dark", "gradient", "gradient-2", "gradient-3", "gradient-4"
    'data_sidebar_image' => env('THEME_DATA_SIDEBAR_IMAGE', 'none'), // "none", "img-1", "img-2", "img-3", "img-4"
    'data_preloader' => env('THEME_DATA_PRELOADER', 'disable'), // "disable", "enable"
    'data_bs_theme' => env('THEME_DATA_BS_THEME', 'light'), // "light", "dark"
    'customizer' => env('THEME_CUSTOMIZER', false), // true or false
];
