<?php

namespace App\Services\PublicationDataQuery\Contracts;

use App\Services\PublicationDataQuery\PublicationResult;

interface DataProvider
{
    public function getDocument(string $doi): ?PublicationResult;
}
