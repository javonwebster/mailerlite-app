<?php


namespace Tests\Feature;

use Database\Seeders\KeySeederValid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MailerLiteApi\MailerLite;
use Tests\TestCase;

/**
 * Class SubscriberTests
 * @package Tests\Feature
 */
class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    private $testSubscriberEmail = 'test@asdf.com';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KeySeederValid::class);
    }

    private function initializeTestSubscriber(){
        try {
            //create a test subscriber
            $subscribersApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->subscribers();

            $subscriber = [
                'email' => $this->testSubscriberEmail,
                'name' => 'Test Subscriber',
                'fields' => [
                    'country'=>'Barbados'
                ]
            ];

            return $subscribersApi->create($subscriber);
        } catch (\Exception $exception) {
            $this->fail('An error occurred: '.$exception->getMessage());
        }
    }

    private function removeTestSubscriber(){
        try {
            //create a test subscriber
            $subscribersApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->subscribers();

            $subscriber = $subscribersApi->find($this->testSubscriberEmail);
            if (!isset($subscriber->error)){
                $subscribersApi->delete($subscriber->id);
            }
        } catch (\Exception $exception) {
            $this->fail('An error occurred: '.$exception->getMessage());
        }
    }

    /**
     * @return void
     * Confirms the subscriber pages loads
     */
    public function test_subscriber_index()
    {
        $response = $this->get('/subscribers');

        $response->assertStatus(200);
        $content = $response->getContent();
    }

    /**
     * @return void
     * Confirms the appropriate error message displays if a subscriber ID is invalid
     */
    public function test_subscriber_edit_invalid()
    {
        $response = $this->get('/subscribers/123/edit');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('An error occurred: Subscriber not found', $content);
    }

    /**
     * @return void
     * Confirms that the edit page loads correctly for a valid subscriber ID
     */
    public function test_subscriber_edit_valid()
    {
        $addedSubscriber = $this->initializeTestSubscriber();

        $response = $this->get('/subscribers/'.$addedSubscriber->id.'/edit');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringNotContainsString('An error occurred: Subscriber not found', $content);
        $this->assertStringContainsString('Test Subscriber', $content);
        $this->assertStringContainsString('Barbados', $content);
    }

    /**
     * @return void
     * Confirms the create page loads
     */
    public function test_subscriber_create()
    {
        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('<label for="email" class="sr-only">Email</label>', $content);
        $this->assertStringContainsString('<label for="name" class="sr-only">Name</label>', $content);
        $this->assertStringContainsString('<label for="country" class="sr-only">Country</label>', $content);
        $this->assertStringNotContainsString('<div class="text-red-500 mt-2 text-sm">', $content);
    }

    /**
     * @return void
     * Test to see that a blocked email address error makes it back to the
     * user. Also this will work for any other errors
     */
    public function test_subscriber_new_blocked_email()
    {
        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/subscribers/create',[
            'email'=> 'test@test.com',
            'name'=> 'Some Name',
            'country'=> 'Spain',
        ])->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Email temporarily blocked', $content);
    }

    /**
     * @return void
     * Test confirms that if an invalid email is entered it will return
     * the error to the user
     */
    public function test_subscriber_new_invalid_email()
    {
        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/subscribers/create',[
            'email'=> '123@123',
            'name'=> '',
            'country'=> '',
        ])->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Invalid email address', $content);
    }

    /**
     * @return void
     * Test confirms that if a subscriber already exist that error
     * is returned to the user.
     */
    public function test_subscriber_new_already_exists()
    {
        $this->initializeTestSubscriber();

        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/subscribers/create',[
            'email'=> $this->testSubscriberEmail,
            'name'=> '',
            'country'=> '',
        ])->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Subscriber already exists', $content);
    }

    /**
     * @return void
     */
    public function test_subscriber_new()
    {
        $this->removeTestSubscriber();

        $response = $this->get('/subscribers/create');
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post('/subscribers/create',[
            'email'=> $this->testSubscriberEmail,
            'name'=> '',
            'country'=> '',
        ])->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('Subscriber was successful added!', $content);
    }

    /**
     * @return void
     * Confirms that a subscriber can be deleted
     */
    public function test_subscriber_delete()
    {
        $subscriber = $this->initializeTestSubscriber();

        $response = $this->get('/subscribers');
        $response->assertStatus(200);

        $response = $this->delete('/subscribers/'.$subscriber->id.'/delete');
        $response->assertStatus(200);
        $this->assertEquals('subscriber deleted',$response->json('message'));

        //confirm that the subscriber was deleted
        try {
            $subscribersApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->subscribers();
            $subscriber = $subscribersApi->find($this->testSubscriberEmail);
            $this->assertNotNull($subscriber->error);
        } catch (\Exception $exception){
            $this->fail('An error occurred: '.$exception->getMessage());
        }

    }

    /**
     * @return void
     * Confirms that if an invalid id is provided an error message is show to the user
     */
    public function test_subscriber_delete_no_exist()
    {
        $response = $this->delete('/subscribers/1234/delete');

        $response->assertStatus(404);
        $this->assertEquals('Subscriber not found',$response->json('message'));
        $this->assertEquals(123,$response->json('code'));
    }

    /**
     * @return void
     * Test the parameters that are sent to the data endpoint, and asserting the response is what we expect
     * This simulates the ajax calls by datatables
     */
    public function test_subscribers_data(){
        $response = $this->post('/subscribers',[
            'start'=>'0',
            'length'=>'10',
            'order'=>[
                '0'=>[
                    'column'=>'0',
                    'dir'=>'asc',
                ]
            ],
            'search'=>[
                'value'=>''
            ]
        ]);
        $response->assertStatus(200);
        $this->assertEquals(10,$response->json('limit'));
        $this->assertEquals(0,$response->json('offset'));
        $this->assertEquals('email',$response->json('field'));
        $this->assertEquals('ASC',$response->json('direction'));
        $this->assertNull($response->json('query'));
    }

    /**
     * Testing where clause on group API
     * @throws \MailerLiteApi\Exceptions\MailerLiteSdkException
     */
    public function test_where_clause_group_api(){
        $groupsApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->groups();
        $groups = $groupsApi->where([
            'active' => [
                '$gt' => 10
            ]
        ])->get();
        $this->assertEmpty($groups->toArray());

        $groups = $groupsApi->where([
            'active' => [
                '$lt' => 2
            ]
        ])->get();
        $this->assertCount(1,$groups->toArray());
    }

//    /**
//     * Testing where clause on subscribers API
//     * @throws \MailerLiteApi\Exceptions\MailerLiteSdkException
//     */
//    public function test_where_clause_subscriber_api(){
//        $this->initializeTestSubscriber();
//
//        $subscribersApi = (new MailerLite(env('TEST_MAILER_API_KEY')))->subscribers();
//
//        $subscriber = $subscribersApi->where(['date_created'=>'Test Subscriber'])->get();// per docs - https://developers.mailerlite.com/docs/parameters
//        dd($subscriber);
//
//        $subscriber = $subscribersApi->where([
//            'email' => [
//                '$like' => $this->testSubscriberEmail
//            ]
//        ])->get();
//    }

}
