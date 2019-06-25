<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5aa159564494aac5ce647ca4a820b3b9
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5aa159564494aac5ce647ca4a820b3b9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5aa159564494aac5ce647ca4a820b3b9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5aa159564494aac5ce647ca4a820b3b9::$classMap;

        }, null, ClassLoader::class);
    }
}