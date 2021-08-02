<?php

namespace Tests\Feature;

use Database\Seeders\KeySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class APIKeyTests extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     * Confirms that no error messages are showing and no API Key has been saved
     */
    public function test_api_key_page_with_no_key()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringNotContainsString('Saved API Key:', $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);
        $this->assertStringNotContainsString('You cannot manage subscribers because you have not saved a valid API Key.', $content);
    }

    /**
     * @return void
     * Test confirms that the query parameter works and displays the error message
     */
    public function test_api_key_page_with_no_key_manage_subscriber()
    {
        $response = $this->get('/?no-key=1');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringNotContainsString('Saved API Key:', $content);
        $this->assertStringContainsString('You cannot manage subscribers because you have not saved a valid API Key.', $content);
    }

    /**
     * @return void
     * Test confirms when a Key is saved the Saved API Key message appears
     */
    public function test_api_key_page_with_key()
    {
        $this->seed(KeySeeder::class);
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Saved API Key:', $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);
    }

    /**
     * @return void
     * Test to see what happens when a user tries to manage subscribers without a saved API Key
     */
    public function test_subscriber_page_with_no_key()
    {
        $response = $this->get('/subscribers');
        $response->assertRedirect('/?no-key=1');
        $content = $response->getContent();
        $this->assertStringNotContainsString('You cannot manage subscribers because you have not saved a valid API Key.', $content);
    }

    /**
     * @return void
     * Test submitting a valid key is saved and the confirmation message displays
     */
    public function test_api_key_page_submit()
    {
        $apiKey = env('TEST_MAILER_API_KEY');
        $response = $this->post('/',[
            'api-key' => $apiKey
        ]);
        $response->assertRedirect('/');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Saved API Key: '.$apiKey, $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);
    }

    /**
     * @return void
     * Test confirms when a Key is saved and the user submits a new key, the old key is updated.
     */
    public function test_api_key_page_with_key_submit_new()
    {
        $this->seed(KeySeeder::class);
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Saved API Key:', $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);

        $apiKey = env('TEST_MAILER_API_KEY');
        $response = $this->post('/',[
            'api-key' => $apiKey
        ]);
        $response->assertRedirect('/');
        $response->assertStatus(302);

        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Saved API Key: '.$apiKey, $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);

        $keys = DB::table('keys')->get();

    }

}
