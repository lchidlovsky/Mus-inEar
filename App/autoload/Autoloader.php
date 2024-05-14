<?php
namespace autoload;

class Autoloader{
    static function register(){
        spl_autoload_register(function ($class) {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $classFile = __DIR__ . '/../' . $path . '.php';
    
            if (file_exists($classFile)) {
                require_once $classFile;
            }
        });
    }
}
?>
