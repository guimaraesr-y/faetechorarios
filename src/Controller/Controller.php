<?php

namespace App\Controller;

use CoffeeCode\Router\Router;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected $router;
    static protected $twig;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Render the specified Twig file with the given data.
     *
     * @param string $twigFile The name of the Twig file.
     * @param array $data An optional array of data to pass to the Twig template.
     * @return void
     */
    public static function render(String $twigFile, Array $data = []): void
    {
        // verify if twig is set and configured
        // if not, set it  
        if(!self::$twig) {
            $loader = new FilesystemLoader(__DIR__ . '/../views');
            self::$twig = new Environment($loader);
        }

        echo self::$twig->render($twigFile, $data);
    }
}