<?php

use Sensorario\Engine\EngineFactory;

require __DIR__ . '/vendor/autoload.php';

$factory = new EngineFactory;
$engine = $factory->getEngine();
$engine->setTemplateFolder('templates/');
$engine->render('griglia');