<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Data\Requests\PostCreateRequest;
use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\UgcPosts;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class UgcPostsTest extends TestCase
{
    public function test_create_ugc_post_v2(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(201, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'urn:li:ugcPost:abc123'
        ])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $ugc = new UgcPosts($client);

        $req = new PostCreateRequest('urn:li:organization:123', 'Hello World');
        $resp = $ugc->create($req);
        $this->assertSame('urn:li:ugcPost:abc123', $resp['id']);
    }
}
