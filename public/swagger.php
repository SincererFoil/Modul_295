<?php
require __DIR__ . "/../vendor/autoload.php";
// Require all scripts within /api directory.

$scripts = glob(__DIR__ . "/../src/*.php");
$controller = glob(__DIR__ . "/../src/controller/*.php");

foreach ($scripts as $script) {
    require $script;
}

foreach ($controller as $script) {
    require $script;
}

// Build and return OpenAPI documentation as YAML.
$result = (new \OpenApi\Builder())
    ->addSource(__DIR__ . "/../src")
    ->build();

header('Content-Type: application/x-yaml');
echo $result->toYaml();