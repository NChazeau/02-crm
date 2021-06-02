<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $admin = new User;
        $admin->firstName = $faker->firstName;
        $admin->lastName = $faker->lastName;
        $admin->email = "admin@email.com";
        $admin->password = "password";
        $admin->roles = ["ROLE_ADMIN", "ROLE_COMPTABLE"];
        $manager->persist($admin);

        $moderator = new User;
        $moderator->firstName = $faker->firstName;
        $moderator->lastName = $faker->lastName;
        $moderator->email = "moderator@email.com";
        $moderator->password = "password";
        $moderator->roles = ["ROLE_MODERATOR"];
        $manager->persist($moderator);
    

        for($u = 0; $u < 5; $u++) {
            $user = new User;
            $user->firstName = $faker->firstName;
            $user->lastName = $faker->lastName;
            $user->email = "user$u@mail.com";
            $user->password = "password";

            $manager->persist($user);

            for($i = 0; $i < random_int(5, 10); $i++) {
                $customer = new Customer;

                $customer->firstName  = $faker->firstName;
                $customer->lastName  = $faker->lastName;
                $customer->email  = "customer$i@mail.com";

                $customer->user = $user;

                $manager->persist($customer);
            }

        }



        $manager->flush();
    }
}