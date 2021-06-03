<?php
namespace App\Tests\Integration\Customer;

use App\Tests\Factory\UserFactory;
use App\Tests\Factory\CustomerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class EditCustomerTest extends WebTestCase
{

    
    public function testAnUnauthenticatedUserCanNotAccessEditForm(){

        $client = static::createClient();

        //Given There is a customer
        $customer = CustomerFactory::createOne();

        //when I navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        //Then I should be redirected to /login
        self::assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotAccessEditForm() {
        $client = static::createClient();
        //Given There is a customer
        $customer = CustomerFactory::createOne();

        // And I have authenticated as a moderator
        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
            ]);

        $client->loginUser($user->object());

        //when I navigate to /customer/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        //Then I should be redirected to /login
        self::assertResponseStatusCodeSame(403);
        
    }

    public function testAnUserCanNotEditACustomerFromAnOtherUser(){
        $client = static::createClient();

        $customer = CustomerFactory::createOne();

        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        $client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseStatusCodeSame(403);

    }


    public function testAnAdminCanEditAnyCustomer() {
        
        $client = static::createClient();

        //Given There is a customer
        $customer = CustomerFactory::createOne();

        // And I am authnticated as an Admin usr
        $user = UserFactory::createOne([
            'roles' => ['ROLE_ADMIN']
        ]);

        $client->loginUser($user->object());

        //when I navigate to /customers/id/edit
        $client->request('GET', '/customers/' . $customer->id . '/edit');

        //then the reponse should be successful
        self::assertResponseIsSuccessful();

        // And there should be a <form> element
        self::assertSelectorExists('form');

        
        //When I submit the form
        $client->submitForm('Enregistrer', [
            'customer[firstName]' => 'MOCK_FIRSTNAME',
            'customer[lastName]' => 'MOCK_LASTNAME',
            'customer[email]' => 'MOCK_EMAIL@email.com'
        ]);

        //Then the customer should be edited
        /** @var CustomerRepository */
        $updatedCustomer = CustomerFactory::find($customer->id);
        self::assertEquals('MOCK_FIRSTNAME', $updatedCustomer->firstName);
        self::assertEquals('MOCK_LASTNAME', $updatedCustomer->lastName);
        self::assertEquals('MOCK_EMAIL@email.com', $updatedCustomer->email);

        // And I am redirected to /customers
    }


    public function testAUserCanEditHisCustomer()
    {
        $client = static::createClient();

        $customer = CustomerFactory::createOne();

        $user = $customer->user;

        $client->loginUser($user);

        $client->request('GET', '/customers/' . $customer->id . '/edit');

        self::assertResponseIsSuccessful();

        self::assertSelectorExists('form');

        $client->submitForm('Enregistrer', [
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