<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Tests\Resources;

use Avansaber\LinkedInApi\Http\ClientConfig;
use Avansaber\LinkedInApi\Http\LinkedInApiClient;
use Avansaber\LinkedInApi\Resources\SocialActions;
use Avansaber\LinkedInApi\Tests\Fixtures\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SocialActionsTest extends TestCase
{
    public function test_comments_and_likes_v2(): void
    {
        $psr17 = new Psr17Factory();
        $fake = new FakeHttpClient();
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));
        $fake->queueResponse(new Response(200, ['Content-Type' => 'application/json'], json_encode(['elements' => []])));

        $client = new LinkedInApiClient($fake, $psr17, $psr17, new ClientConfig('202401'), 'token');
        $sa = new SocialActions($client);
        $urn = 'urn:li:ugcPost:abc123';

        $comments = $sa->comments($urn);
        $likes = $sa->likes($urn);
        $this->assertArrayHasKey('elements', $comments);
        $this->assertArrayHasKey('elements', $likes);
    }
}
