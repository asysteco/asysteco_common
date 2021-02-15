<?php

// Configuración de variables de entorno de trabajo

$basedir = dirname($_SERVER['DOCUMENT_ROOT']);
$subdir = '/' . $options['Centro'];
$Titulo = $options['Centro'];

$dirs = [
    'public' => './',
    'inc' => $basedir . $subdir . '/inc/',
    'class' => $basedir . $subdir . '/class/',
    'bdConfig' => $basedir . $subdir . '/config_instituto.php',
    'Editar' => $basedir . $subdir . '/inc/Editar/',
    'Exportar' => $basedir . $subdir . '/inc/Admon/Exportar/',
    'Fichaje' => $basedir . $subdir . '/inc/Fichaje/',
    'Form' => $basedir . $subdir . '/inc/Form/',
    'Helper' => $basedir . $subdir . '/inc/Helper/',
    'Horarios' => $basedir . $subdir . '/inc/Horarios/',
    'Importar' => $basedir . $subdir . '/inc/Importar/',
    'Interfaces' => $basedir . $subdir . '/inc/Interfaces/',
    'Listar' => $basedir . $subdir . '/inc/Admon/Listar/',
    'Login' => $basedir . $subdir . '/inc/Login/',
    'phpqrcode' => $basedir . $subdir . '/inc/phpqrcode/',
    'Profesores' => $basedir . $subdir . '/inc/Profesores/',
    'Qr' => $basedir . $subdir . '/inc/Qr/',
    'Valida' => $basedir . $subdir . '/inc/Valida/',
    'Admon' => $basedir . $subdir . '/inc/Admon/',
    'Downloads' => $basedir . $subdir . '/inc/Downloads/',
    'CP' => $basedir . $subdir . '/inc/CP/'
];
