<?php

namespace Lisennk\GifFlopper\VK;

use Lisennk\GifFlopper\Interfaces\FileInterface;
use VK\VK;
use GuzzleHttp\Client;

class Document
{
    protected $vk;
    protected $client;
    protected $name;
    protected $content;

    public function __construct(string $filename, FileInterface $file, VK $api)
    {
        $this->vk = $api;
        $this->client = new Client();
        $this->content = $file->getBlob();
        $this->saveAs($filename);
    }

    protected function saveAs(string $filename): string
    {
        $uploadUrl = $this->getUploadUrl();
        $file = $this->uploadFile($uploadUrl, $filename, $this->content);
        return $this->saveFile($file);
    }

    protected function getUploadUrl(): string
    {
        return $this->vk->api('docs.getUploadServer')['response']['upload_url'];
    }

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

    protected function saveFile($file): string
    {
        return $this->vk->api('docs.save', [
            'file' => $file,
        ])['response'][0]['url'];
    }
}