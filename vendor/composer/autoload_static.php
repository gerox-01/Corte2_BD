<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4b1bd4b3481e30a35f9df5011c77d49e
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'ReCaptcha\\' => 10,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ReCaptcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/google/recaptcha/src/ReCaptcha',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4b1bd4b3481e30a35f9df5011c77d49e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4b1bd4b3481e30a35f9df5011c77d49e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4b1bd4b3481e30a35f9df5011c77d49e::$classMap;

        }, null, ClassLoader::class);
    }
}
