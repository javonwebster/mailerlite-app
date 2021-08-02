<?php


namespace Tests\Feature;

use Database\Seeders\KeySeederValid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MailerLiteApi\MailerLite;
use Tests\TestCase;

class SubscriberTests extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KeySeederValid::class);
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
    public function test_subscriber_edit_invalid_id()
    {
        $response = $this->get('/subscribers/123/edit');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('An error occurred: Subscriber not found', $content);
    }

    /**
     * @return void
     */
    public function test_subscriber_edit_invalid()
    {
        $response = $this->get('/subscribers/123/edit');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('An error occurred: Subscriber not found', $content);
    }

    /**
     */
    public function test_subscriber_edit_valid()
    {
        try {
            //create a test subscriber
            $subscribersApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->subscribers();

            $subscriber = [
                'email' => 'javonwebster95@gmail.com',
                'name' => 'Test Subscriber',
                'fields' => [
                    'country'=>'Barbados'
                ]
            ];

            $addedSubscriber = $subscribersApi->create($subscriber);

            $response = $this->get('/subscribers/'.$addedSubscriber->id.'/edit');
            $response->assertStatus(200);
            $content = $response->getContent();
            $this->assertStringNotContainsString('An error occurred: Subscriber not found', $content);
            $this->assertStringContainsString('Test Subscriber', $content);
            $this->assertStringContainsString('Barbados', $content);
        } catch (\Exception $exception) {
            $this->fail('An error occurred: '.$exception->getMessage());
        }
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
        $response = $this->delete('/subscribers/1/delete');

        $response->assertStatus(200);
        $this->assertEquals('subscriber deleted',$response->json('message'));
    }

}
