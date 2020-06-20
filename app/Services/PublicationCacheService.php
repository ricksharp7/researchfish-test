<?php

namespace App\Services;

use \Log;
use App\Models\Publication;
use App\Services\PublicationDataProvider\Contracts\DataProvider;
use Exception;

class PublicationCacheService
{
    /**
     * Retrieves a single publication by its DOI
     *
     * @param string $doi
     * @return Publication|null
     */
    public function getPublication(string $doi): ?Publication
    {
        // First, attempt to retrieve it from the database
        $publication = $this->getPublicationFromDatabase($doi);
        if ($publication) {
            return $publication;
        }

        // If not found, attempt to retrieve it from the publication service
        return $this->getPublicationFromExternalService($doi);
    }

    /**
     * Retrieve the publication from the database
     *
     * @param string $doi
     * @return Publication|null
     */
    private function getPublicationFromDatabase(string $doi): ?Publication
    {
        return Publication::forDoi($doi)->first();
    }

    /**
     * Retrieve the publication from an external service
     *
     * @param string $doi
     * @return Publication|null
     */
    private function getPublicationFromExternalService(string $doi): ?Publication
    {
        // Get the service by it's contract. Let the Service Provider decide which implementation to provide
        $provider = app()->make(DataProvider::class);
        
        try {
            $publicationResult = $provider->getDocument($doi);
            if ($publicationResult) {
                return Publication::createFromPublicationResult($publicationResult);
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
        return null;
    }
}
