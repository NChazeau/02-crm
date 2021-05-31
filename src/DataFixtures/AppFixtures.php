<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 20; $i++){
            $customer = new Customer;
            
            $customer->firstName = 'Prénom' . $i;
            $customer->lastName = 'Nom' . $i;
            $customer->email = "customers$i@mail.com";

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
