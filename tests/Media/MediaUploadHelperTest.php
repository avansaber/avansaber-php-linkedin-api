<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Media;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Media\MediaUploadHelper;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class MediaUploadHelperTest extends TestCase
{
    public function test_initialize_and_upload_image(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'value' => [
                'uploadUrl' => 'https://upload.example.com/abc',
                'asset' => 'urn:li:image:123'
            ]
        ])));
        $fake->queueResponse(new Response(201, [], ''));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $helper = new MediaUploadHelper($client);

        $init = $helper->initializeImageUpload('urn:li:organization:123');
        $this->assertArrayHasKey('value', $init);
        $uploadUrl = $init['value']['uploadUrl'];
        $this->assertTrue($helper->uploadBinary($uploadUrl, 'binarydata'));
    }

    public function test_initialize_video_and_document(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['value' => ['uploadUrl' => 'https://upload.example.com/v', 'asset' => 'urn:li:video:1']])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['value' => ['uploadUrl' => 'https://upload.example.com/d', 'asset' => 'urn:li:document:1']])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $helper = new MediaUploadHelper($client);

        $vid = $helper->initializeVideoUpload('urn:li:organization:123', 1000);
        $this->assertArrayHasKey('value', $vid);
        $doc = $helper->initializeDocumentUpload('urn:li:organization:123');
        $this->assertArrayHasKey('value', $doc);
    }

    public function test_upload_chunk(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(206, [], ''));
        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $helper = new MediaUploadHelper($client);
        $this->assertTrue($helper->uploadChunk('https://upload.example.com/v', 'bytes', 'bytes 0-3/10'));
    }
}
