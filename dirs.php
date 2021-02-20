<?php

// Configuración de variables de entorno de trabajo

$basedir = dirname($_SERVER['DOCUMENT_ROOT']);
$subdir = '/' . $options['Centro'];
$includes = $basedir . $subdir;
$inc = $basedir . $subdir . '/inc/';
$Titulo = $options['Centro'];

$dirs = [
    'public' => './',
    'class' => $includes . '/class/',
    'inc' => $inc . '/inc/',
    'bdConfig' => $includes . '/config_instituto.php',
    'ChangePass' => $inc . 'ChangePass/',
    'FirstPassChange' => $inc . 'FirstPassChange/',
    'Lectivos' => $inc . 'Lectivos/',
    'Qr' => $inc . 'Qr/',
    'Horarios' => $inc . 'Horarios/',
    'Asistencias' => $inc . 'Asistencias/',
    'Profesores' => $inc . 'Profesores/',
    'Marcajes' => $inc . 'Marcajes/',
    'Guardias' => $inc . 'Guardias/',
    'CP' => $inc . 'CP/',
    'Notificaciones' => $inc . 'Notificaciones/',
    'Admon' => $inc . 'Admon/',
    'Exportar' => $inc . 'Admon/Exportar/',
    'Listar' => $inc . 'Admon/Listar/',
    'FicharQr' => $inc . 'FicharQr/',
    'FicharManual' => $inc . 'FicharManual/',
    'Downloads' => $inc . 'Downloads/',
    'CleanTmp' => $inc . 'CleanTmp/',   

    'Form' => $inc . 'Form/',
    'Helper' => $inc . 'Helper/',
    'Importar' => $inc . 'Importar/',
    'Interfaces' => $inc . 'Interfaces/',
    'Login' => $inc . 'Login/',
    'phpqrcode' => $inc . 'phpqrcode/',
    'Valida' => $inc . 'Valida/'
];
