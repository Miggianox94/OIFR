<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read https://symfony.com/doc/current/setup.html#checking-symfony-application-configuration-and-setup
// for more information
umask(0000);


require __DIR__.'/../vendor/autoload.php';

#it removes the dependency to app_dev.php
$debug = (bool) getenv('SYMFONY_DEBUG');
$env = getenv('SYMFONY_ENV') ?: 'prod';

if ($debug) {
    Debug::enable();
}

if ('prod' === $env) {
    header('HTTP/1.0 403 Forbidden');
    //exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
    $kernel = new AppKernel('prod', $debug);
}
else{
    $kernel = new AppKernel($env, $debug);
}


if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
