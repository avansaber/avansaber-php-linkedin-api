<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Http;

final class PaginatorIterator
{
    /**
     * Iterate through paginated results calling the provided fetcher.
     *
     * @param callable(int $start, int $count): array<string,mixed> $fetchPage
     * @return \Generator<int, array<string, mixed>, void, void>
     */
    public static function iterate(callable $fetchPage, int $count = 10): \Generator
    {
        $start = 0;
        while (true) {
            $response = $fetchPage($start, $count);
            $paging = Paginator::extract($response);
            $elements = $response['elements'] ?? [];
            if (!is_array($elements) || count($elements) === 0) {
                break;
            }
            foreach ($elements as $element) {
                yield $element;
            }
            $start = $paging['start'] + $paging['count'];
            $total = $paging['total'];
            if ($total !== null && $start >= $total) {
                break;
            }
        }
    }
}
