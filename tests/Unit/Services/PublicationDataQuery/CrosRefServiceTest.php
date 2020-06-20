<?php

namespace Tests\Unit\Services\PublicationDataQuery;

use App\Exceptions\ProviderDocumentNotFoundException;
use App\Services\PublicationDataQuery\Providers\CrosRef;
use Tests\TestCase;

class CrosRefServiceTest extends TestCase
{
    public function testItInstantiatesTheClass()
    {
        $class = app()->make(CrosRef::class);
        $this->assertInstanceOf(CrosRef::class, $class);
    }

    public function testItRetrievesASingleEntryWithAValidDoi()
    {
        $doi = '10.1037/0003-066X.59.1.29';
        // Actual sample result
        $expectedJson = '{"indexed":{"date-parts":[[2020,3,29]],"date-time":"2020-03-29T06:10:13Z","timestamp":1585462213947},"reference-count":105,"publisher":"American Psychological Association (APA)","issue":"1","content-domain":{"domain":[],"crossmark-restriction":false},"short-container-title":["American Psychologist"],"DOI":"10.1037\/0003-066x.59.1.29","type":"journal-article","created":{"date-parts":[[2004,1,21]],"date-time":"2004-01-21T14:31:19Z","timestamp":1074695479000},"page":"29-40","source":"Crossref","is-referenced-by-count":87,"title":["How the Mind Hurts and Heals the Body."],"prefix":"10.1037","volume":"59","author":[{"given":"Oakley","family":"Ray","sequence":"first","affiliation":[]}],"member":"15","published-online":{"date-parts":[[2004]]},"container-title":["American Psychologist"],"original-title":[],"language":"en","link":[{"URL":"http:\/\/psycnet.apa.org\/journals\/amp\/59\/1\/29.pdf","content-type":"unspecified","content-version":"vor","intended-application":"similarity-checking"}],"deposited":{"date-parts":[[2020,3,29]],"date-time":"2020-03-29T05:38:13Z","timestamp":1585460293000},"score":1.0,"subtitle":[],"short-title":[],"issued":{"date-parts":[[2004]]},"references-count":105,"journal-issue":{"published-online":{"date-parts":[[2004]]},"issue":"1"},"alternative-id":["2004-10043-004","14736318"],"URL":"http:\/\/dx.doi.org\/10.1037\/0003-066x.59.1.29","relation":{},"ISSN":["1935-990X","0003-066X"],"issn-type":[{"value":"0003-066X","type":"print"},{"value":"1935-990X","type":"electronic"}],"subject":["General Psychology","General Medicine"]}';
        $expectedData = json_decode($expectedJson, true);
        $expectedData = CrosRef::createPublicationResultFromMessage($expectedData);
        $provider = app()->make(CrosRef::class);
        $result = $provider->getDocument($doi);
        $this->assertEquals($expectedData, $result);
    }

    public function testItThrowsAnExceptionIfTheDocumentIsNotFound()
    {
        $this->expectException(ProviderDocumentNotFoundException::class);
        $doi = '10.0000/000-nope.111';
        $provider = app()->make(CrosRef::class);
        $result = $provider->getDocument($doi);
        $this->assertNull($result);
    }
}
