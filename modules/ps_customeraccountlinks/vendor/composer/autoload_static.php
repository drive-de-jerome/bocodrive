<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit59e59bb8d783675d266462d5b6926384
{
    public static $classMap = array (
        'Ps_Customeraccountlinks' => __DIR__ . '/../..' . '/ps_customeraccountlinks.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit59e59bb8d783675d266462d5b6926384::$classMap;

        }, null, ClassLoader::class);
    }
}
