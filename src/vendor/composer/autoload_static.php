<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95c77370d25853970f268167969ebf18
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Config\\Database' => __DIR__ . '/../..' . '/app/Config/Database.php',
        'App\\Controller\\CommentController' => __DIR__ . '/../..' . '/app/Controller/CommentController.php',
        'App\\Controller\\PostController' => __DIR__ . '/../..' . '/app/Controller/PostController.php',
        'App\\Controller\\UserController' => __DIR__ . '/../..' . '/app/Controller/UserController.php',
        'App\\Manager\\CommentManager' => __DIR__ . '/../..' . '/app/Manager/CommentManager.php',
        'App\\Manager\\PostManager' => __DIR__ . '/../..' . '/app/Manager/PostManager.php',
        'App\\Manager\\UserManager' => __DIR__ . '/../..' . '/app/Manager/UserManager.php',
        'App\\Model\\Comment' => __DIR__ . '/../..' . '/app/Model/Comment.php',
        'App\\Model\\EntityModel' => __DIR__ . '/../..' . '/app/Model/EntityModel.php',
        'App\\Model\\Post' => __DIR__ . '/../..' . '/app/Model/Post.php',
        'App\\Model\\User' => __DIR__ . '/../..' . '/app/Model/User.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95c77370d25853970f268167969ebf18::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95c77370d25853970f268167969ebf18::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit95c77370d25853970f268167969ebf18::$classMap;

        }, null, ClassLoader::class);
    }
}