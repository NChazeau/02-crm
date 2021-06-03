<?php
namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase {
    public function testInvoiceClassExists(){
        
        $this->assertTrue(class_exists('App\Entity\Invoice'));
        
    }

    public function testInvoiceClassHasCorreectProperties(){
        
        $this->assertTrue(property_exists('App\Entity\Invoice', 'id'));
        $this->assertTrue(property_exists('App\Entity\Invoice', 'amount'));
        $this->assertTrue(property_exists('App\Entity\Invoice', 'createdAt'));
        $this->assertTrue(property_exists('App\Entity\Invoice', 'customer'));
       
    }
}