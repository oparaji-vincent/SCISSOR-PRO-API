<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit30986490394dd9340f0613b44ca507ad
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

        spl_autoload_register(array('ComposerAutoloaderInit30986490394dd9340f0613b44ca507ad', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit30986490394dd9340f0613b44ca507ad', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit30986490394dd9340f0613b44ca507ad::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
