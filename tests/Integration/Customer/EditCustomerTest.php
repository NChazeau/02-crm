<?php


namespace App\Tests\Integration\Customer;


use App\Repository\CustomerRepository;
use App\Tests\Factory\CustomerFactory;
use App\Tests\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditCustomerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testAnUnauthenticatedUserCanNotAccessEditForm()
    {
        $customer = CustomerFactory::createOne();

        $this->client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotAccessEditForm()
    {
        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseStatusCodeSame(403);
    }

    public function testAUserCanNotEditACustomerFromAnOterUser()
    {
        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne();

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseStatusCodeSame(403);
    }

    public function testAnAdminCanEditAnyCustomer()
    {
        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN']
        ]);

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseIsSuccessful();

        self::assertSelectorExists('form');

        $this->client->submitForm('Enregistrer', [
            'customer[firstName]' => 'MOCK_FIRSTNAME',
            'customer[lastName]' => 'MOCK_LASTNAME',
            'customer[email]' => 'MOCK_EMAIL@email.com'
        ]);

        /** @var CustomerRepository */
        $updatedCustomer = CustomerFactory::find($customer->id);
        self::assertEquals('MOCK_FIRSTNAME', $updatedCustomer->firstName);
        self::assertEquals('MOCK_LASTNAME', $updatedCustomer->lastName);
        self::assertEquals('MOCK_EMAIL@email.com', $updatedCustomer->email);
    }

   
}