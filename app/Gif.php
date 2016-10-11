<?php

namespace Lisennk\GifFlopper;

use Lisennk\GifFlopper\Interfaces\FileInterface;

/**
 * Class Gif
 * @package Lisennk\GifFlopper
 */
class Gif implements FileInterface
{
    /**
     * @var string URL Path to image
     */
    protected $url;

    /**
     * This class uses Linux tools to perform download and flopping,
     * so this array contain list of commands that will be piped and executed
     *
     * @var array
     */
    protected $commands;

    /**
     * Gif constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->download($url);
    }

    /**
     * Downloads file from URL, following redirects and fake user agent
     *
     * @param string $url
     * @return Gif
     */
    public function download(string $url): self
    {
        $ua  = 'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5';
        $this->commands[] = 'curl -s -L -A "' . $ua . '" "'.$url.'"';

        return $this;
    }

    /**
     * Flop is "horizontal mirror" of image
     *
     * @return Gif
     */
    public function flop(): self
    {
        $this->commands[] = 'convert gif:- -flop gif:-';

        return $this;
    }

    /**
     * Returns files binary content
     *
     * @return string
     */
    public function getBlob(): string
    {
        $command = $this->getCommand();
        return shell_exec($command);
    }

    /**
     * Returns piped shell commands
     *
     * @return string
     */
    protected function getCommand(): string
    {
        return implode('|', $this->commands);
    }
}