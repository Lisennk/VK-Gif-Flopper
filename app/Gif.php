<?php

namespace Lisennk\GifFlopper;

use Lisennk\GifFlopper\Interfaces\FileInterface;
use Imagick;

/**
 * Class Gif
 * @package Lisennk\GifFlopper
 */
class Gif implements FileInterface
{
    /**
     * @var string Image blob
     */
    protected $gif;

    /**
     * @var string URL Path to image
     */
    protected $url;

    /**
     * Gif constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Flop is "horizontal mirror" of image
     *
     * @return Gif
     */
    public function flop(): self
    {
        system('convert ' . $this->url . ' -flop -', $this->gif);
        return $this;
    }

    /**
     * Returns files binary content
     *
     * @return string
     */
    public function getBlob(): string
    {
        return $this->gif;
    }
}