#!/usr/bin/env php
<?php

if (is_dir($vendor = __DIR__ . '/../vendor')) {
    require($vendor . '/autoload.php');
} elseif (is_dir($vendor = __DIR__ . '/../../../../vendor')) {
    require($vendor . '/autoload.php');
} else {
    die(
        'You must set up the project dependencies, run the following commands:' . PHP_EOL .
            'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
            'php composer.phar install' . PHP_EOL
    );
}


$crawler = new \CliCrawler\Crawler();
$crawler->run();
