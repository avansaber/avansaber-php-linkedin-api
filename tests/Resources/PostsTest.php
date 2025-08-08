<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\Posts;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class PostsTest extends TestCase
{
    public function test_get_post_rest(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'abc',
            'author' => 'urn:li:organization:123',
            'commentary' => 'Hello',
        ])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $posts = new Posts($client);
        $post = $posts->get('abc');
        $this->assertSame('abc', $post->id);
        $this->assertSame('urn:li:organization:123', $post->author);
        $this->assertSame('Hello', $post->commentary);
    }
}
