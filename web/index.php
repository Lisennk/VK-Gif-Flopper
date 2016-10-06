<?php

require_once('../vendor/autoload.php');

use Lisennk\GifFlopper\GifFlopper;
use VK\VK;

if (isset($_POST['submit'])) {
    $config = json_decode(file_get_contents('../config.json'));
    $vk = new VK($config->app_id, $config->api_secret, $config->access_token);

    (new GifFlopper($vk))->get($_POST['gif_url'])->flop()->saveAs('devnull.gif');
}

echo file_get_contents('view.html');
