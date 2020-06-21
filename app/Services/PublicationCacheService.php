<?php

namespace App\Services;

use \Log;
use App\Models\Publication;
use App\Services\PublicationDataProvider\Contracts\DataProvider;
use Exception;
use Illuminate\Support\Collection;

class PublicationCacheService
{
    /**
     * Retrieves a single publication by its DOI
     *
     * @param string $doi
     * @return Collection|null
     */
    public function getPublication(string $doi): ?Collection
    {
        // First, attempt to retrieve it from the database
        Log::info('Retreiving from database: ' . $doi);
        $publications = $this->getPublicationsFromDatabase($doi);
        if ($publications->count()) {
            Log::info('Found ' . $publications->count() . ' records');
            return $publications;
        }

        // If not found, attempt to retrieve it from the publication service
        Log::info('Retreiving from external service');
        return $this->getPublicationFromExternalService($doi);
    }

    /**
     * Retrieve the publication from the database
     *
     * @param string $doi
     * @return Collection|null
     */
    private function getPublicationsFromDatabase(string $doi): ?Collection
    {
        return Publication::forDoi($doi)->get();
    }

    /**
     * Retrieve the publication from an external service
     *
     * @param string $doi
     * @return Collection|null
     */
    private function getPublicationFromExternalService(string $doi): ?Collection
    {
        // Get the service by it's contract. Let the Service Provider decide which implementation to provide
        $provider = app()->make(DataProvider::class);

        try {
            $publicationResult = $provider->getDocument($doi);
            if ($publicationResult) {
                return collect([Publication::createFromPublicationResult($publicationResult)]);
            }
        } catch (Exception $e) {
            Log::warning($e->getMessage());
        }
        return null;
    }
}
