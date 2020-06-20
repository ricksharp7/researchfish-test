<?php

namespace App\Services\PublicationDataQuery\Providers;

use App\Exceptions\InvalidProviderConfigException;
use App\Exceptions\ProviderDocumentNotFoundException;
use App\Exceptions\ProviderRequestFailedException;
use App\Services\PublicationDataQuery\Contracts\DataProvider as DataProviderInterface;
use Illuminate\Support\Facades\Http;

class CrosRef implements DataProviderInterface
{

    /**
     * Retrieves a single document from the provider
     *
     * @param string $doi
     * @return array|null
     */
    public function getDocument(string $doi): ?array
    {
        $response = Http::withHeaders($this->buildHeaders())
            ->get($this->buildDocumentUri($doi));
        if ($response->serverError()) {
            throw new ProviderRequestFailedException($response->body());
        }
        if ($response->clientError()) {
            throw new ProviderDocumentNotFoundException();
        }
        $data = $response->json();
        if (!isset($data["status"]) || $data["status"] !== "ok") {
            throw new ProviderRequestFailedException("Invalid response");
        }
        if ($data['message-type'] === 'work') {
            return $data['message'];
        }
        return null;
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
