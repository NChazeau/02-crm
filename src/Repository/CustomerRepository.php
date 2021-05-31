<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

// Pour info : Customer::class === "App\Entity\Customer"

class CustomerRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Customer::class);
    }
}