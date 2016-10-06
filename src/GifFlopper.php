<?php

namespace Lisennk\GifFlopper;

use VK\VK;
use Imagick;
use Lisennk\GifFlopper\Document;

class GifFlopper
{
    protected $gif;
    protected $api;

    /**
     * GifFlopper constructor.
     * @param VK $api
     */
    public function __construct(VK $api)
    {
        $this->api = $api;
    }

    /**
     * Downloads GIF from URL
     *
     * @param string $url
     * @return GifFlopper
     */
    public function get(string $url): self
    {
        $this->gif = (new Imagick($url))->coalesceImages();

        return $this;
    }

    public function flop(): self
    {
        foreach ($this->gif as $frame) {
            $frame->flopImage();
        }

        return $this;
    }

    public function saveAs(string $name): self
    {
        $this->gif->deconstructImages();

        $file = $this->gif->getImagesBlob();
        $doc = new Document($name, $file, $this->api);

        return $this;
    }
}