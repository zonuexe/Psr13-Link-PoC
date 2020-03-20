<?php

declare(strict_types=1);

namespace zonuexe\WebLink;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$use_safe = !!($_GET['safe'] ?? '');

$link_factory = $use_safe ? new SafeLinkFactory() : new FigLinkFactory();
$current = $link_factory->create(['current'], $_SERVER['REQUEST_URI']);

$emitted = emitLinkHeader([$current]);
var_dump($emitted);
