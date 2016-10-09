<?php

require_once('../vendor/autoload.php');

use Lisennk\GifFlopper\Gif;
use Lisennk\GifFlopper\VK\Documents;
use Lisennk\GifFlopper\VK\ApiFactory;

try {
    $api = ApiFactory::makeWith('../config.json');
    $documents = new Documents($api);

    $gifDocument = $documents->get($_GET['url']);

    $gif = new Gif($gifDocument['url']);
    $gif->flop();

    $documents->add('dev-null.gif', $gif);

    echo 'Success';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}