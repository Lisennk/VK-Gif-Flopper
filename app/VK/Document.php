<?php

namespace Lisennk\GifFlopper\VK;

use Lisennk\GifFlopper\Interfaces\FileInterface;
use VK\VK;
use GuzzleHttp\Client;

/**
 * Class Document
 * @package Lisennk\GifFlopper\VK
 */
class Document
{
    /**
     * @var VK
     */
    protected $api;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * File blob
     *
     * @var string
     */
    protected $blob;

    /**
     * Document constructor.
     * @param string $filename
     * @param FileInterface $file
     * @param VK $api
     */
    public function __construct(string $filename, FileInterface $file, VK $api)
    {
        $this->api = $api;
        $this->client = new Client();
        $this->blob = $file->getBlob();
        $this->saveAs($filename);
    }

    /**
     * Save doc on vk.com as $filename
     *
     * @param string $filename
     * @return string Uploaded Doc Url
     */
    protected function saveAs(string $filename): string
    {
        $uploadUrl = $this->getUploadUrl();
        $file = $this->uploadFile($uploadUrl, $filename, $this->blob);
        return $this->saveFile($file);
    }

    /**
     * @return string Url where to upload document
     */
    protected function getUploadUrl(): string
    {
        return $this->api->api('docs.getUploadServer')['response']['upload_url'];
    }

    /**
     * Perform file upload
     *
     * @param string $url
     * @param string $filename
     * @param string $content
     * @return string
     */
    protected function uploadFile(string $url, string $filename, string $content): string
    {
        $response = $this->client->request('POST', $url, [
            'multipart' => [
                [
                    'Content-type' => 'multipart/form-data',
                    'name'     => 'file',
                    'contents' => $content,
                    'filename' => $filename
                ],
            ]
        ])->getBody()->getContents();

        return json_decode($response)->file;
    }

    /**
     * Saves uploaded doc on vk.com
     *
     * @param $file
     * @return string
     */
    protected function saveFile($file): string
    {
        return $this->api->api('docs.save', [
            'file' => $file,
        ])['response'][0]['url'];
    }
}