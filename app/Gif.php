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
     * Image
     *
     * @var Imagick
     */
    protected $gif;

    /**
     * Gif constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->gif = (new Imagick($url))->coalesceImages();
    }

    /**
     * Flop is "horizontal mirror" of image
     *
     * @return Gif
     */
    public function flop(): self
    {
        foreach ($this->gif as $frame) {
            $frame->flopImage();
        }

        return $this;
    }

    /**
     * Returns files binary content
     *
     * @return string
     */
    public function getBlob(): string
    {
        $this->gif->deconstructImages();
        return $this->gif->getImagesBlob();
    }
}