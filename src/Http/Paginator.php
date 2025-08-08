<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Http;

final class Paginator
{
    /**
     * Extracts paging information from either REST (start/count/total) or REST.li (paging object) responses.
     *
     * @param array<string, mixed> $response
     * @return array{start:int,count:int,total:?int}
     */
    public static function extract(array $response): array
    {
        if (isset($response['paging']) && is_array($response['paging'])) {
            $paging = $response['paging'];
            return [
                'start' => (int) ($paging['start'] ?? 0),
                'count' => (int) ($paging['count'] ?? 10),
                'total' => isset($paging['total']) ? (int) $paging['total'] : null,
            ];
        }
        return [
            'start' => (int) ($response['start'] ?? 0),
            'count' => (int) ($response['count'] ?? 10),
            'total' => isset($response['total']) ? (int) $response['total'] : null,
        ];
    }
}
