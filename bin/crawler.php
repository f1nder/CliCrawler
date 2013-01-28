#!/usr/bin/env php
<?php

//autoload
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

//init main class
$crawler = new \CliCrawler\Crawler();

//validation input options
try {
    $crawler->validationInputOptions();
}catch (Exception $e){
    printf($e->getMessage()."\n");
    exit(1);
}

//run crawler
$crawler->run();
