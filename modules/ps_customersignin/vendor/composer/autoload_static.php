<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7a320b948d73b4d80cffc10b7c708e66
{
    public static $classMap = array (
        'Ps_CustomerSignIn' => __DIR__ . '/../..' . '/ps_customersignin.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit7a320b948d73b4d80cffc10b7c708e66::$classMap;

        }, null, ClassLoader::class);
    }
}