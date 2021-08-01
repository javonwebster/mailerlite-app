<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_api_key_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_subscriber_index()
    {
        $response = $this->get('/subscribers');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_subscriber_edit()
    {
        $response = $this->get('/subscribers/1/edit');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_subscriber_create()
    {
        $response = $this->get('/subscribers/create');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_subscriber_delete()
    {
        $response = $this->get('/subscribers/1/delete');

        $response->assertStatus(200);
        $this->assertEquals('subscriber deleted',$response->json('message'));
    }
}
