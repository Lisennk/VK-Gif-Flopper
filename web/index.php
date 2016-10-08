<?php

require_once('../vendor/autoload.php');

use VK\VK;
use Lisennk\GifFlopper\Gif;
use Lisennk\GifFlopper\VK\Document;
if (isset($_POST['submit'])) {
    $config = json_decode(file_get_contents('../config.json'));
    $vk = new VK($config->app_id, $config->api_secret, $config->access_token);

    $gif = new Gif($_POST['gif_url']);
    $gif->flop();

    (new Document($vk, $gif))->name('dev-null.gif');
}

echo file_get_contents('view.html');
