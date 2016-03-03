<?php

$autoloader = require(dirname(__DIR__) . '/vendor/autoload.php');
$autoloader->add('tests\\', dirname(__DIR__));

use Symfony\Component\HttpFoundation\Request;
use LumengPHP\Kernel\AppKernel;

$request = Request::createFromGlobals();

$kernel = new AppKernel(__DIR__ . '/config/config.yml');

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
