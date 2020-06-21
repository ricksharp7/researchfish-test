<?php

namespace Tests\Unit\Services\PublicationDataProvider;

use \PublicationCache;
use App\Models\Publication;
use App\Services\PublicationCacheService;
use App\Services\PublicationDataProvider\Contracts\DataProvider;
use App\Services\PublicationDataProvider\Providers\CrosRef;
use App\Services\PublicationDataProvider\PublicationResult;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class PublicationCacheTest extends TestCase
{
    use DatabaseMigrations;

    public function testItInstantiatesTheService()
    {
        $class = app()->make(PublicationCacheService::class);
        $this->assertInstanceOf(PublicationCacheService::class, $class);
    }

    public function testItReturnsFromTheDatabaseIfExists()
    {
        $document = factory(\App\Models\Publication::class)->create();

        $publications = PublicationCache::getPublication($document->doi);
        $this->assertInstanceOf(Collection::class, $publications);
        $publication = $publications->first();
        $this->assertEquals($document->doi, $publication->doi);
        $this->assertEquals($document->title, $publication->title);
    }

    public function testItRetrievesThePublicationFromAnExternalProviderIfNotCached()
    {
        // Mock the CrosRef service so that we don't actually call it here
        $this->instance(DataProvider::class, Mockery::mock(CrosRef::class, function ($mock) {
            $mock->shouldReceive('getDocument')
                ->once()
                ->with('10.000/00000')
                ->andReturn(null);
        }));
        PublicationCache::getPublication('10.000/00000');
    }

    public function testItResultsFromAnExternalProviderAreStoredInTheDatabase()
    {
        $publicationResult = new PublicationResult();
        $publicationResult->doi = '10.000/00000';
        $publicationResult->title = 'Some title';
        $publicationResult->publisher = 'Some publisher';
        $publicationResult->url = 'Some URL';
        $publicationResult->meta = json_decode('{"indexed":{"date-parts":[[2020,3,29]],"date-time":"2020-03-29T06:10:13Z","timestamp":1585462213947},"reference-count":105,"publisher":"American Psychological Association (APA)","issue":"1","content-domain":{"domain":[],"crossmark-restriction":false},"short-container-title":["American Psychologist"],"DOI":"10.1037\/0003-066x.59.1.29","type":"journal-article","created":{"date-parts":[[2004,1,21]],"date-time":"2004-01-21T14:31:19Z","timestamp":1074695479000},"page":"29-40","source":"Crossref","is-referenced-by-count":87,"title":["How the Mind Hurts and Heals the Body."],"prefix":"10.1037","volume":"59","author":[{"given":"Oakley","family":"Ray","sequence":"first","affiliation":[]}],"member":"15","published-online":{"date-parts":[[2004]]},"container-title":["American Psychologist"],"original-title":[],"language":"en","link":[{"URL":"http:\/\/psycnet.apa.org\/journals\/amp\/59\/1\/29.pdf","content-type":"unspecified","content-version":"vor","intended-application":"similarity-checking"}],"deposited":{"date-parts":[[2020,3,29]],"date-time":"2020-03-29T05:38:13Z","timestamp":1585460293000},"score":1.0,"subtitle":[],"short-title":[],"issued":{"date-parts":[[2004]]},"references-count":105,"journal-issue":{"published-online":{"date-parts":[[2004]]},"issue":"1"},"alternative-id":["2004-10043-004","14736318"],"URL":"http:\/\/dx.doi.org\/10.1037\/0003-066x.59.1.29","relation":{},"ISSN":["1935-990X","0003-066X"],"issn-type":[{"value":"0003-066X","type":"print"},{"value":"1935-990X","type":"electronic"}],"subject":["General Psychology","General Medicine"]}', true);

        // Mock the CrosRef service so that we don't actually call it here
        $this->instance(DataProvider::class, Mockery::mock(CrosRef::class, function ($mock) use ($publicationResult) {
            $mock->shouldReceive('getDocument')
                ->once()
                ->with('10.000/00000')
                ->andReturn($publicationResult);
        }));
        $publications = PublicationCache::getPublication('10.000/00000');

        $this->assertInstanceOf(Collection::class, $publications);

        $this->assertDatabaseHas('publications', [
            'doi' => $publicationResult->doi,
            'title' => $publicationResult->title,
            'publisher' => $publicationResult->publisher,
            'url' => $publicationResult->url,
            'meta' => json_encode($publicationResult->meta),
        ]);
    }

    public function testItReturnsNullIfThePublicationIsNotFound()
    {
        // Mock the CrosRef service so that we don't actually call it here
        $this->instance(DataProvider::class, Mockery::mock(CrosRef::class, function ($mock) {
            $mock->shouldReceive('getDocument')
                ->once()
                ->with('10.000/00000')
                ->andReturn(null);
        }));
        $publication = PublicationCache::getPublication('10.000/00000');

        $this->assertNull($publication);
    }
}
