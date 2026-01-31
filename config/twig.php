<?php
/**
 * Twig Template Engine Configuration
 * Initializes Twig with auto-escaping and caching
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

// Define template directory
$templateDir = realpath(__DIR__ . '/../templates');
$cacheDir = realpath(__DIR__ . '/../cache');

// Create Twig loader
$loader = new FilesystemLoader($templateDir);

// Create Twig environment
$twig = new Environment($loader, [
    'cache' => $cacheDir ?: false,
    'auto_reload' => true,
    'autoescape' => 'html', // Enable auto-escaping for XSS prevention
]);

// Add debug extension (development)
$twig->addExtension(new DebugExtension());

// Add custom functions
$twig->addFunction(new \Twig\TwigFunction('htmlspecialchars', 'htmlspecialchars'));
