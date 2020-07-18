<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . "/src/Entity"],
    $isDevMode,
    $proxyDir,
    $cache,
    $useSimpleAnnotationReader
);

$conn = [
    'driver' => 'pdo_sqlite',
    'path'   => __DIR__ . '/db.sqlite',
];

$entityManager = EntityManager::create($conn, $config);
