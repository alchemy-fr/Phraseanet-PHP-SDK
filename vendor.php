<?php

chdir(__DIR__);

set_time_limit(0);

$composer = __DIR__ . '/composer.phar';

if (!file_exists($composer))
{
  system('curl -s http://getcomposer.org/installer | php');
  system('chmod +x ' . $composer);
  system($composer . ' install');
}

if (!is_executable($composer))
{
  system('chmod +x ' . $composer);
}

system($composer . ' self-update');
system($composer . ' update');

system('git submodule init');
system('git submodule update');

$iterator = new RecursiveDirectoryIterator(__DIR__ . '/vendor/alchemy/');

foreach ($iterator as $file)
{
  /* @var $file SplFileInfo */
  if ($file->isDir())
  {
    $cmd = sprintf('cd %s && git submodule init && git submodule update', escapeshellarg($file->getPathname()));
    system($cmd);
  }
}