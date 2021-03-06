<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita3598a134b5ba2c0127b02186532da72
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FacebookPixelPlugin\\Integration\\' => 32,
            'FacebookPixelPlugin\\Core\\' => 25,
            'FacebookPixelPlugin\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FacebookPixelPlugin\\Integration\\' => 
        array (
            0 => __DIR__ . '/../..' . '/integration',
        ),
        'FacebookPixelPlugin\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/core',
        ),
        'FacebookPixelPlugin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita3598a134b5ba2c0127b02186532da72::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita3598a134b5ba2c0127b02186532da72::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
