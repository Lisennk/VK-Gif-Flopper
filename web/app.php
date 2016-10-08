<?php

require_once('../vendor/autoload.php');

use Lisennk\GifFlopper\Gif;
use Lisennk\GifFlopper\VK\Document;
use Lisennk\GifFlopper\VK\ApiFactory;

$api = ApiFactory::makeWith('../config.json');

$gif = new Gif($_GET['url']);
$gif->flop();

new Document('dev-null.gif', $gif, $api);