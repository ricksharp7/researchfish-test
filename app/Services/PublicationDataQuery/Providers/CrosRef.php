<?php

namespace App\Services\PublicationDataQuery\Providers;

use App\Exceptions\InvalidProviderConfigException;
use App\Exceptions\ProviderDocumentNotFoundException;
use App\Exceptions\ProviderRequestFailedException;
use App\Services\PublicationDataQuery\Contracts\DataProvider as DataProviderInterface;
use App\Services\PublicationDataQuery\PublicationResult;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

class CrosRef implements DataProviderInterface
{

    /**
     * Retrieves a single document from the provider
     *
     * @param string $doi
     * @return PublicationResult|null
     */
    public function getDocument(string $doi): ?PublicationResult
    {
        try {
            $url = $this->buildHeaders();
            Log::info('Requesting document from CrosRef: ' . $url);
            $response = Http::withHeaders($url)
                ->get($this->buildDocumentUri($doi));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw new ProviderRequestFailedException($e->getMessage());
        }

        // Throw an exception if something goes wrong. Let the caller decide what to do with the exception.
        if ($response->serverError()) {
            Log::error($response->body());
            throw new ProviderRequestFailedException($response->body());
        }
        if ($response->clientError()) {
            Log::info('Document not found');
            throw new ProviderDocumentNotFoundException('Document not found');
        }
        $data = $response->json();
        if (!isset($data["status"]) || $data["status"] !== "ok") {
            Log::warning('Status returned was not OK');
            throw new ProviderRequestFailedException("Invalid response");
        }

        // If a single publication was returned, return it in a standardised format
        if ($data['message-type'] === 'work') {
            Log::info('Document found');
            return $this->createPublicationResultFromMessage($data['message']);
        }

        // Fallback: return null
        Log::warning('Reached fallback: ' . $response->getBody());
        return null;
    }

    /**
     * Converts the response from CrosRef into a standardised response object
     *
     * @param array $data
     * @return PublicationResult
     */
    public static function createPublicationResultFromMessage(array $data): PublicationResult
    {
        $publicationResult = new PublicationResult;
        $publicationResult->doi = $data['DOI'] ?? null;
        $publicationResult->title = isset($data['title']) ? implode(' ', $data['title']) : '';
        $publicationResult->publisher = $data['publisher'] ?? null;
        $publicationResult->url = $data['URL'] ?? null;
        $publicationResult->meta = $data ?? null;
        return $publicationResult;
    }

    /**
     * Builds the full URL to retrieve a specific document
     *
     * @param string $doi
     * @return string
     */
    private function buildDocumentUri(string $doi): string
    {
        $url = config('publishers.services.crosref.url', '');
        if (empty($url)) {
            throw new InvalidProviderConfigException('Missing provider URL');
        }
        $endpoint = config('publishers.services.crosref.document_endpoint', '');
        if (empty($endpoint)) {
            throw new InvalidProviderConfigException('Missing document endpoint');
        }
        $separator = '';
        if (substr($endpoint, 0, 1) !== '/') {
            $separator = '/';
        }
        return $url . $separator . $endpoint . '/' . urlencode($doi);
    }

    /**
     * Generates the request headers
     *
     * @return array
     */
    private function buildHeaders(): array
    {
        return config('publishers.services.crosref.headers', []);
    }
}
