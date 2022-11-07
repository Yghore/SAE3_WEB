<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit491a57b5c2bdabbadcead7de96e8220d
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'iutnc\\netvod\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'iutnc\\netvod\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit491a57b5c2bdabbadcead7de96e8220d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit491a57b5c2bdabbadcead7de96e8220d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
