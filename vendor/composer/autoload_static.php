<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd556886c5cf1c597b31e7e419881de3e
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SRAG\\ILIAS\\Plugins\\MetaData\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SRAG\\ILIAS\\Plugins\\MetaData\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd556886c5cf1c597b31e7e419881de3e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd556886c5cf1c597b31e7e419881de3e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}