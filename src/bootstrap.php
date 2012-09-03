<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/config/config.yml"));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/config/config_dev.yml"));