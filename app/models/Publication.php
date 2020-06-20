<?php

namespace App\Models;

use App\Services\PublicationDataQuery\PublicationResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $fillable = [
        'doi',
        'publisher',
        'title',
        'url',
        'meta',
    ];

    // The meta column is JSON. This will allow us to treat it like an associative array.
    public $casts = [
        'meta' => 'array',
    ];

    public function scopeForDoi(Builder $query, string $doi): Builder
    {
        return $query->where('doi', $doi);
    }

    /**
     * Creates a record from a standardised publication result
     *
     * @param PublicationResult $publicationResult
     * @return Publication
     */
    public static function createFromPublicationResult(PublicationResult $publicationResult): Publication
    {
        return self::create([
            'doi' => $publicationResult->doi,
            'title' => $publicationResult->title,
            'publisher' => $publicationResult->publisher,
            'url' => $publicationResult->url,
            'meta' => $publicationResult->meta,
        ]);
    }
}
