<?php
namespace App\Tests\Integration\Entity;

use DateTime;
use App\Entity\Invoice;
use App\Tests\Factory\CustomerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//Plus de fonctionnalité que le testCase mais moins que le WebTestCase
class InvoiceTest extends KernelTestCase
{
    public function testWeCanPersistAndFlushAnInvoice(){
        static::bootKernel();
        //Given we have an Entiry Manager
        $em = static::$container->get(EntityManagerInterface::class);

        // And there is a Customer
        $customer = CustomerFactory::createOne();
        // And there is a new Invoice
        $invoice = new Invoice;
        $invoice->amount = 1000;
        $invoice->createdAt = new DateTime('2021-01-01 14:00:00');
        $invoice->customer = $customer->object();
        //When I persist and flush the news Invoice
        $em->persist($invoice);
        $em->flush();
        //Then the Invoice should exist in the database
        $this->assertNotNull($invoice->id);
    }

    public function testWeCanAccessACustomersInvoices(){
        static::bootKernel();

        //Given we have a Customer
        $customer

        //And there are 5 invoices linked to this customer

        //When I ask for the customer's invoices 

        //tHEN WE SHOULD FIND 5 INVOICES INSIDE THE CUSTOMER

        //Then
    }
}