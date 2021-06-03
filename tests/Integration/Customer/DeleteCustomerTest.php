<?php
namespace App\Tests\Integration\Customer;

use App\Tests\Factory\UserFactory;
use App\Tests\Factory\CustomerFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;

class DeleteCustomerTest extends WebTestCase
{
    public function testAnAuthenticatedUserCanDeleteAcustomer(){
        $client = static::createClient();

        //Given I am authenticated as a user
        $user = UserFactory::createOne();

        $client->loginUser($user->object());

        //And there is a customer linked to this user
        $customer = CustomerFactory::createOne([
            'user' => $user
        ])->object();

        //When I navigate to /CUSTOMERS:ID:DELETE
        $client->request('GET', '/customers/' . $customer->id . '/delete');
        //tHEN i SHOULD BE REDIRECTED TO :CustomCredentials
        $this->assertResponseRedirects("/customers");
        //aND THE CUSTOMERS DOES NOT EXISTE ANYMORE
       $deletedCustomer = CustomerFactory::findBy([
           'email' => $customer->email,
           'firstName' => $customer->firstName,
           'lastName' => $customer->lastName
       ])[0] ?? null;
        $this->assertNull($deletedCustomer);
    }

    public function provideRoles() {
        return [
            ['ROLE_MODERATOR'],
            ['ROLE_ADMIN']
        ];
    }

    /**
     * @dataProvider provideRoles
     */

    public function testAModeratorCanDeleteAcustomer(string $role){
        $client = static::createClient();

        //Given I am authenticated as a user
        $user = UserFactory::createOne([
            'roles' => [$role]
        ]
        );

        $client->loginUser($user->object());

        //And there is a customer 
        $customer = CustomerFactory::createOne()->object();

        //When I navigate to /CUSTOMERS:ID:DELETE
        $client->request('GET', '/customers/' . $customer->id . '/delete');

        //tHEN i SHOULD BE REDIRECTED TO :CustomCredentials
        $this->assertResponseRedirects("/customers");

        //aND THE CUSTOMERS DOES NOT EXISTE ANYMORE
       $deletedCustomer = CustomerFactory::findBy([
           'email' => $customer->email,
           'firstName' => $customer->firstName,
           'lastName' => $customer->lastName
       ])[0] ?? null;
        $this->assertNull($deletedCustomer);
    }
}