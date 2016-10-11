<?php

namespace Lisennk\GifFlopper\VK;

use Lisennk\GifFlopper\Interfaces\FileInterface;
use VK\VK;
use GuzzleHttp\Client;

/**
 * Class Document
 * @package Lisennk\GifFlopper\VK
 */
class Documents
{
    /**
     * @var VK
     */
    protected $vk;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Document constructor.
     * @param VK $api
     */
    public function __construct(VK $api)
    {
        $this->vk = $api;
        $this->client = new Client();
    }

    /**
     * Returns info about document by its URL
     *
     * @see https://vk.com/dev/docs.getById
     * @param string $url
     * @return array
     */
    public function get(string $url): array
    {
        $id = $this->getDocIdFromString($url);
        return $id ? $this->getById($id) : [];
    }


    /**
     * Returns info about document by its ID
     *
     * @see https://vk.com/dev/docs.getById
     * @param string $id
     * @return array
     */
    public function getById(string $id): array
    {
        return $this->vk->api('docs.getById', [
            'docs' => $id
        ])['response'][0];
    }

    /**
     * Returns VK Doc ID form any string
     *
     * @param string $string For example, "https://vk.com/doc237223974_438108346"
     * @return string For example, "237223974_438108346" or "" if there is no ID in the string
     */
    protected function getDocIdFromString(string $string): string
    {
        return preg_match("/(\d+)_(\d+)/i", $string, $matches) ? $matches[0] : '';
    }

    /**
     * Save doc on vk.com as $filename
     *
     * @param string $filename
     * @param FileInterface $file
     * @return string Uploaded Doc Url
     */
    public function add(string $filename, FileInterface $file): string
    {
        $uploadUrl = $this->getUploadUrl();
        $file = $this->uploadFile($uploadUrl, $filename, $file->getBlob());
        return $this->saveFile($file);
    }

    /**
     * @return string Url where to upload document
     */
    protected function getUploadUrl(): string
    {
        return $this->vk->api('docs.getUploadServer')['response']['upload_url'];
    }

    /**
     * Perform file upload
     *
     * @param string $url
     * @param string $filename
     * @param string $content
     * @throws \Exception
     * @return string
     */
    protected function uploadFile(string $url, string $filename, string $content): string
    {
        $response = json_decode($this->client->request('POST', $url, [
            'multipart' => [
                [
                    'Content-type' => 'multipart/form-data',
                    'name'     => 'file',
                    'contents' => $content,
                    'filename' => $filename
                ],
            ]
        ])->getBody()->getContents());

        if ($response->error) throw new \Exception($response->error);
        return $response->file;
    }

    /**
     * Saves uploaded doc on vk.com
     *
     * @param $file
     * @return string
     */
    protected function saveFile($file): string
    {
        return $this->vk->api('docs.save', [
            'file' => $file,
        ])['response'][0]['url'];
    }
}