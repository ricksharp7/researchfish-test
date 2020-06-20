<?php

namespace App\Services\PublicationDataQuery\Contracts;

interface DataProvider
{
    public function getDocument(string $doi): ?array;
}
