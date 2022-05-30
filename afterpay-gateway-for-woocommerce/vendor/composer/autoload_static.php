<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd70eb747942ddcbae100006fbfef960d
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'Afterpay\\SDK\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Afterpay\\SDK\\' => 
        array (
            0 => __DIR__ . '/..' . '/afterpay-global/afterpay-sdk-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd70eb747942ddcbae100006fbfef960d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd70eb747942ddcbae100006fbfef960d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd70eb747942ddcbae100006fbfef960d::$classMap;

        }, null, ClassLoader::class);
    }
}
