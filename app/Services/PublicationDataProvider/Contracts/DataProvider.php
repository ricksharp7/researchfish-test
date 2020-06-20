<?php

namespace App\Services\PublicationDataProvider\Contracts;

use App\Services\PublicationDataProvider\PublicationResult;

interface DataProvider
{
    public function getDocument(string $doi): ?PublicationResult;
}
