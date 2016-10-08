<?php

namespace Lisennk\GifFlopper;

use Lisennk\GifFlopper\Interfaces\FileInterface;
use Imagick;

class Gif implements FileInterface
{
    protected $gif;
    protected $api;


    public function __construct(string $url)
    {
        $this->gif = (new Imagick($url))->coalesceImages();
    }

    public function flop(): self
    {
        foreach ($this->gif as $frame) {
            $frame->flopImage();
        }

        return $this;
    }

    public function getBlob(): string
    {
        $this->gif->deconstructImages();
        return $this->gif->getImagesBlob();
    }
}