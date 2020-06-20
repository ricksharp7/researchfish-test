<?php

namespace Tests\Feature;

use App\Facades\PublicationCacheFacade;
use App\Services\PublicationDataProvider\PublicationResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItReturnsAnInvalidRequestIfTheDoiIsMissing()
    {
        $response = $this->getJson('/api/publication');
        $response->assertStatus(422);
    }

    public function testItReturnsAnInvalidRequestIfTheDoiIsEmpty()
    {
        $response = $this->getJson('/api/publication?doi=');
        $response->assertStatus(422);
    }

    public function testItReturnsA404IfNotFound()
    {
        $doi = '10.1234/1234';
        // Mock the cache service to report that the publication was not found
        PublicationCacheFacade::shouldReceive('getPublication')
            ->once()
            ->with($doi)
            ->andReturn(null);

        $response = $this->getJson('/api/publication?doi=' . $doi);

        $response->assertStatus(404);
    }

    public function testItRequestsTheDocumentFromTheCache()
    {
        // Creates, but does not persist to the database
        $publication = factory(\App\Models\Publication::class)->make();

        // Mock the cache service. The actual functionality of the cache service is covered in the unit tests
        PublicationCacheFacade::shouldReceive('getPublication')
            ->once()
            ->with($publication->doi)
            ->andReturn($publication);

        $response = $this->getJson('/api/publication?doi=' . $publication->doi);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'publication' => [
                    'doi' => $publication->doi,
                    'title' => $publication->title,
                    'publisher' => $publication->publisher,
                    'url' => $publication->url,
                ]
            ]);
    }
}
