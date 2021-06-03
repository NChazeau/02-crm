<?php

namespace App\Tests\Integration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Factory\UserFactory;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateCustomerTest extends WebTestCase {

    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = self::$container->get(UserRepository::class);
    }

    public function testAnUnauthenticatedUserCanNotCreateACustomer(): void
    {
        $this->client->request('GET', '/customers/create');

        self::assertResponseRedirects('/login');
    }

    public function testAModeratorCanNotCreateCustomer(): void
    {

        $user = UserFactory::createOne([
            'roles' => ['ROLE_MODERATOR']
        ]);

        $this->client->loginUser($user->object());

        $this->client->request('GET', '/customers/create');

        self::assertResponseStatusCodeSame(403);
    }


    public function testAnAuthenticatedUserCanCreateACustomer(): void
    {
        $user = UserFactory::createOne();

        $this->client->loginUser($user->object());


        //When I call /customer/create
        $crawler = $this->client->request('GET', '/customers/create');

        self::assertResponseIsSuccessful();

        //self::assertEquals(2, $crawler->filter('form')->count());  si plusieur
        self::assertSelectorExists('form');

        $this->client->submitForm('Enregistrer', [
            'customer[firstName]' => 'Jérome',
            'customer[lastName]' => 'Dupont',
            'customer[email]' => 'jdupont@email.com'
        ]);

        /** @var CustomerRepository */
        $repository = self::$container->get(CustomerRepository::class);

        $customer = $repository->findOneBy([
            'firstName' => 'Jérome',
            'lastName' => 'Dupont',
            'email' => 'jdupont@email.com'
        ]);

        self::assertNotNull($customer);

        self::assertResponseRedirects('/customers');
    }
}


/*
class CreateCustomerTest extends WebTestCase{ 


    public function testAnUnauthenticatedUserCanNotCreateACustomer(){
        //Si j'appelle /customer/create sans être connecté
        $client = static::createClient();

        $client->request('GET', 'customers/create');

        //Alors je devrais être redirigé vers le login
        //status : 302
        //Dans les ehaders, le location devrait être /login
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertEquals('/login', $client->getResponse()->headers->get('Location'));

    }



    public function testAModeratorCanNotCreateCustomer(){

        //1 Lancer l'application symfony
        self::bootKernel();
        $client = static::createClient();

        //2 Connexion en tant qu'un utilisateur qui a le rôle de moérateur
        //Aller chercher un user en base de données qui soit modérator
        $repository = static::$container->get(UserRepository::class);

        $user = $repository->find(45);

        $client->loginUser($user);

        //3 Accès à la page d/customers/create

        $client->request('GET', '/customers/create');
        //4 Assurons nous qu'on nous a foutu à la porte
        $this->assertEquals(403, $client->getResponse()->getStatusCode);
        //$client->getResponse()->getStatusCode();

    }
}
*/