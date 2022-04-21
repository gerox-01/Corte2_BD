<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit4b1bd4b3481e30a35f9df5011c77d49e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit4b1bd4b3481e30a35f9df5011c77d49e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit4b1bd4b3481e30a35f9df5011c77d49e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        \Composer\Autoload\ComposerStaticInit4b1bd4b3481e30a35f9df5011c77d49e::getInitializer($loader)();

        $loader->register(true);

        return $loader;
    }
}
