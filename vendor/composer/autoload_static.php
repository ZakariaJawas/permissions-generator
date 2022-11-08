<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5be5030441e99b33e6e019601f51401a
{
    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'ZakariaJawas\\PermissionsGenerator\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ZakariaJawas\\PermissionsGenerator\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5be5030441e99b33e6e019601f51401a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5be5030441e99b33e6e019601f51401a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5be5030441e99b33e6e019601f51401a::$classMap;

        }, null, ClassLoader::class);
    }
}
