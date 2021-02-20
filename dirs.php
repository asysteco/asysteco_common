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
    'Editar' => $inc . 'Editar/',
    'Exportar' => $inc . 'Admon/Exportar/',
    'Fichaje' => $inc . 'Fichaje/',
    'Form' => $inc . 'Form/',
    'Helper' => $inc . 'Helper/',
    'Horarios' => $inc . 'Horarios/',
    'Importar' => $inc . 'Importar/',
    'Interfaces' => $inc . 'Interfaces/',
    'Listar' => $inc . 'Admon/Listar/',
    'Login' => $inc . 'Login/',
    'phpqrcode' => $inc . 'phpqrcode/',
    'Profesores' => $inc . 'Profesores/',
    'Valida' => $inc . 'Valida/',

    'Notificaciones' => $inc . 'Notificaciones/',
    'Admon' => $inc . 'Admon/',
    'Downloads' => $inc . 'Downloads/',
    'CP' => $inc . 'CP/',
    'ChangePass' => $inc . 'ChangePass/',
    'FirstPassChange' => $inc . 'FirstPassChange/',
    'Lectivos' => $inc . 'Lectivos/',
    'Qr' => $inc . 'Qr/',
    'Asistencias' => $inc . 'Asistencias/',
    'Marcajes' => $inc . 'Marcajes/',
    'FicharQr' => $inc . 'FicharQr/',
    'FicharManual' => $inc . 'FicharManual/',
    'CleanTmp' => $inc . 'CleanTmp/'
];
