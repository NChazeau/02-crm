<?php

namespace App\Tests\Integration\Security;

use App\Tests\Factory\UserFactory;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterTest extends WebTestCase
{
    public function testAUserCanRegister(){
        
        $client = static::createClient();

        //Quand je me rend sur /regiister
        $client->request('GET', '/register');

        //Alors la réponse est OK (200) 
        $this->assertResponseIsSuccessful();
        // Et je vois un <form>
        $this->assertSelectorExists('form');
        //Quand je soumet le formulaire
        $client->submitForm('Confirmer', [
            'register[firstName]' => 'MOCK_FIRSTNAME', 
            'register[lastName]' => 'MOCK_LASTNAME',
            'register[email]' => 'MOCK_EMAIL@mail.com',
            'register[password]' => 'password',
        ]);


        //Alors je suis redirigé vers le /login
        $this->assertResponseRedirects('/login');
        //Et mes infos sont dans la base de données
            $user = UserFactory::findBy(['email' => 'MOCK_EMAIL@mail.com'])[0] ?? null;

            $this->assertNotNull($user);
            $this->assertEquals('MOCK_FIRSTNAME', $user->firstName);
            $this->assertEquals('MOCK_LASTNAME', $user->lastName);
        //Et le mot de pass est hashé
        self::assertNotEquals('password', $user->password);

    }

    public function testARegisterUserCanLogin()
    {
        $client = static::createClient();
        $user = UserFactory::createOne([
            'email' => 'test@mail.com',
            'password' => 'mdp'
        ]);
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $client->submitForm('Connexion', [
            'form[username]' => 'test@mail.com',
            'form[password]' => 'mdp'
        ]);
        $this->assertResponseRedirects('/customers');
        $security = static::$container->get(Security::class);
        $this->assertTrue($security->isGranted('ROLE_USER'));
        $loggedUser = $security->getUser();
        $this->assertEquals($user->firstName, $loggedUser->firstName);
        $this->assertEquals($user->lastName, $loggedUser->lastName);
        $this->assertEquals($user->email, $loggedUser->email);
        $this->assertEquals($user->password, $loggedUser->password);
    }


    public function testItWillNotLoginBadCredentials()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        
        $client->submitForm('Connexion', [
            'form[username]' => 'MOCK_EMAIL@email.com',
            'form[password]' => 'password'
        ]);

        $this->assertResponseStatusCodeSame(200);

        /** @var Security */
        $security = self::$container->get(Security::class);

        $this->assertNotTrue($security->isGranted('ROLE_USER'));

        $this->assertResponseStatusCodeSame(200);
    }


}