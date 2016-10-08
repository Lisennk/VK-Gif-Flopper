<?php

namespace Lisennk\GifFlopper\VK;

use VK\VK;

/**
 * Class ApiFactory
 * @package Lisennk\GifFlopper\VK
 */
class ApiFactory
{
    /**
     * Creates VK API instance with configuration
     *
     * @param string $configPath
     * @return VK
     */
    public static function makeWith(string $configPath)
    {
        $config = json_decode(file_get_contents($configPath));
        return new VK($config->app_id, $config->api_secret, $config->access_token);
    }
}