<?php


namespace Tests\Feature;

use Database\Seeders\KeySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTests extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KeySeeder::class);
    }

    /**
     * @return void
     */
    public function test_subscriber_index()
    {
        $response = $this->get('/subscribers');

        $response->assertStatus(200);
        $content = $response->getContent();
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
