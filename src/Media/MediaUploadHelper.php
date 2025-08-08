<?php

declare(strict_types=1);

namespace Avansaber\LinkedInApi\Media;

use Avansaber\LinkedInApi\Http\LinkedInApiClient;

final class MediaUploadHelper
{
    public function __construct(
        private readonly LinkedInApiClient $client
    ) {
    }

    /**
     * Initialize an image upload for the given owner URN (member or organization).
     * Returns the raw API response, which should include upload instructions/URL and asset URN.
     *
     * @return array<string, mixed>
     */
    public function initializeImageUpload(string $ownerUrn): array
    {
        $body = [
            'initializeUploadRequest' => [
                'owner' => $ownerUrn,
            ],
        ];
        return $this->client->post('images?action=initializeUpload', $body, [], true);
    }

    /**
     * Initialize a video upload for the given owner URN.
     *
     * @return array<string, mixed>
     */
    public function initializeVideoUpload(string $ownerUrn, int $fileSizeBytes, bool $uploadCaptions = false, bool $uploadThumbnail = false): array
    {
        $body = [
            'initializeUploadRequest' => [
                'owner' => $ownerUrn,
                'fileSizeBytes' => $fileSizeBytes,
                'uploadCaptions' => $uploadCaptions,
                'uploadThumbnail' => $uploadThumbnail,
            ],
        ];
        return $this->client->post('videos?action=initializeUpload', $body, [], true);
    }

    /**
     * Initialize a document upload for the given owner URN.
     *
     * @return array<string, mixed>
     */
    public function initializeDocumentUpload(string $ownerUrn): array
    {
        $body = [
            'initializeUploadRequest' => [
                'owner' => $ownerUrn,
            ],
        ];
        return $this->client->post('documents?action=initializeUpload', $body, [], true);
    }

    /**
     * Upload binary content to the given upload URL using PUT.
     * Returns true if 2xx.
     */
    public function uploadBinary(string $uploadUrl, string $contents): bool
    {
        $response = $this->client->request(
            'PUT',
            $uploadUrl,
            $contents,
            [
                'Content-Type' => 'application/octet-stream',
            ],
            true
        );
        $code = $response->getStatusCode();
        return $code >= 200 && $code < 300;
    }

    /**
     * Upload a single chunk to the given upload URL using PUT and Content-Range header.
     * Example header: bytes 0-524287/1048576
     */
    public function uploadChunk(string $uploadUrl, string $chunkBytes, string $contentRange): bool
    {
        $response = $this->client->request(
            'PUT',
            $uploadUrl,
            $chunkBytes,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Range' => $contentRange,
            ],
            true
        );
        $code = $response->getStatusCode();
        return $code >= 200 && $code < 300;
    }
}
