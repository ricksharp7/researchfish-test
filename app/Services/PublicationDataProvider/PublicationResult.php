<?php

namespace App\Services\PublicationDataProvider;

/**
 * A standard result object that is agnostic of the service used to populate it
 */
class PublicationResult
{
    public $doi = null;
    public $publisher = null;
    public $title = null;
    public $url = null;
    public $meta = null;
}
