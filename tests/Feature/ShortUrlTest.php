<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function the_api_returns_a_successful_link_and_response(): void
    {
        $url = [
            'url' => 'https://google.com/',
        ];

        $response = $this->postJson('/api/short', $url);
        $data = $response->json();
        $this->assertEquals($url['url'], $data['url']);
        $response->assertStatus(201);

    }

    /** @test */
    public function the_api_returns_an_error_validaton_response(): void
    {
        $url = [
            'url' => 'google.com',
        ];

        $response = $this->postJson('/api/short', $url);

        $response->assertStatus(422);

    }

    /** @test */
    public function the_api_returns_a_not_found_response(): void
    {
        $short_link = 'Q2dF4g3';

        $response = $this->getJson('/api/short'. $short_link);

        $response->assertStatus(404);

    }

    /** @test */
    public function the_application_redirects_to_requested_link_by_short_url(): void
    {
        $url = [
            'url' => 'https://google.com/',
        ];

        $responseApi = $this->postJson('/api/short', $url);
        $data = $responseApi->json();

        $response = $this->get($data['short_url']);

        $response->assertRedirect($data['url']);
    }

}
