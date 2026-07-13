<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Production-safe entrypoint for shared hosting environments such as InfinityFree.
// It supports deployments where the web root contains the public folder contents.
$maintenanceCandidates = [
    __DIR__.'/../storage/framework/maintenance.php',
    __DIR__.'/storage/framework/maintenance.php',
    dirname(__DIR__).'/storage/framework/maintenance.php',
];

foreach ($maintenanceCandidates as $maintenance) {
    if (file_exists($maintenance)) {
        require $maintenance;
        break;
    }
}

$autoloadCandidates = [
    __DIR__.'/../vendor/autoload.php',
    __DIR__.'/vendor/autoload.php',
    dirname(__DIR__).'/vendor/autoload.php',
];

$autoloadPath = null;
foreach ($autoloadCandidates as $candidate) {
    if (file_exists($candidate)) {
        $autoloadPath = $candidate;
        break;
    }
}

if ($autoloadPath === null) {
    exit('Composer autoload file was not found. Please upload the application dependencies before deploying.');
}

require $autoloadPath;

$bootstrapCandidates = [
    __DIR__.'/../bootstrap/app.php',
    __DIR__.'/bootstrap/app.php',
    dirname(__DIR__).'/bootstrap/app.php',
];

$bootstrapPath = null;
foreach ($bootstrapCandidates as $candidate) {
    if (file_exists($candidate)) {
        $bootstrapPath = $candidate;
        break;
    }
}

if ($bootstrapPath === null) {
    exit('Laravel bootstrap file was not found. Please upload the application files to the hosting root.');
}

/** @var Application $app */
$app = require_once $bootstrapPath;

$app->handleRequest(Request::capture());
