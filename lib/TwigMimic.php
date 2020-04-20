<?php

declare(strict_types=1);

namespace Uzulla;

use DirectoryIterator;
use InvalidArgumentException;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigMimic
{
    // TODO: to configure-ble.
    private static string $baseClassName = "\\Project\\ViewState";
    private static string $templateBasePath = __DIR__ . '/../template';

    public static function fullAuto(): void
    {
        error_log("REQUEST_URI => {$_SERVER['REQUEST_URI']}");

        if ($_SERVER['REQUEST_URI'] == '/') {
            echo "<html lang='en'><body><h1>List of paths</h1>";
            echo "<ul>";

            $vs_dir = new DirectoryIterator(__DIR__."/../src/ViewState/");
            if(!$vs_dir->isDir()){
                throw new InvalidArgumentException("not found ViewState dir");
            }

            foreach($vs_dir as $file){
                if (!$file->isFile()) continue;
                if( 'php' !== $file->getExtension()) continue;
                $file_name = htmlspecialchars($file->getBasename('.php'), ENT_QUOTES, 'UTF-8');
                echo "<li><a href='/{$file_name}'>{$file_name}</a></li>";
            }

            echo "</ul>";
            exit;
        }

        # Too DANGER, but this is deveoper tool.
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        # deny ../
        if (preg_match("|\.\./|u", $_SERVER['REQUEST_URI']) || preg_match("|\.\./|u", $path)) {
            echo "'../' is not allowed";
            exit;
        }

        error_log("parsed path => {$path}");

        # Generate ViewState class name (TOO DANGER, don't use in production code.)
        # replace / to \
        $class_name = static::$baseClassName . preg_replace("|/|u", "\\", $path);
        error_log("Class => {$class_name}");
        try {
            $data = new $class_name();
        } catch (Throwable $e) {
            echo $e->getMessage();
            exit;
        }

        # generate template path (TOO DANGER, don't use in production code.)
        # add Twig
        $template_path = $path . ".twig";
        error_log("template_path => {$template_path}");

        try {
            static::renderAndSend($template_path, $data);
        } catch (Throwable $e) {
            echo $e->getMessage();
            exit;
        }
    }

    private static function getTwig(): Environment
    {
        $loader = new FilesystemLoader(static::$templateBasePath);
        return new Environment($loader);
    }

    /**
     * @param string|null $template_path
     * @param null $data
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderAndSend(string $template_path = null, $data = null): void
    {
        echo static::render($template_path, $data);
    }

    /**
     * @param string|null $template_path
     * @param null $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function render(string $template_path = null, $data = null): string
    {
        error_log("Data dump => " . print_r((array)$data, true));
        return static::getTwig()->render($template_path, (array)$data);
    }

}