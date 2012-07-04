<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in($dir = __DIR__ . '/../src')
;

return new Sami($iterator, array(
    'title'                => 'Phraseanet PHP SDK API',
    'theme'                => 'enhanced',
    'build_dir'            => __DIR__.'/source/API/API',
    'cache_dir'            => __DIR__.'/source/API/API/cache',
    'default_opened_level' => 2,
));
