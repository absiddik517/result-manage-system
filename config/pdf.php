<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '14',
    'default_font'             => 'sans-serif',
    'margin_left'              => 20,
    'margin_right'             => 20,
    'margin_top'               => 20,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'RMS',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => true,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => base_path('storage/images/logo.png'),
    'watermark_image_alpha'    => 0.1,
    'watermark_image_size'     => 'P',
    'watermark_image_position' => 'P',
    'custom_font_dir'  => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'nikosh' => [ // Custom font settings for Bengali.
            'R' => 'Nikosh.ttf', // Bold font file.
            'B' => 'Nikosh.ttf', // Bold font file.
            'useOTL' => 0xFF, // OTL support.
            'useKashida' => 75 // Kashida width.
        ],
      // ...add as many as you want.
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
