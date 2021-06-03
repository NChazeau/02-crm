<?php


namespace App\Tests\Unit\Security;


use App\Entity\User;
use App\Security\Voter\CustomerVoter;
use Monolog\Test\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class CustomerVoterTest extends TestCase
{
    public function testItWorks()
    {
        $customerVoter = new CustomerVoter();

        $attributs = [
            'CAN_REMOVE',
            'CAN_EDIT',
            'CAN_LIST_CUSTOMERS',
            'CAN_LIST_ALL_CUSTOMERS',
            'CAN_CREATE_CUSTOMER'
        ];

        $mockTokenInterface = $this->createMock(TokenInterface::class);

        foreach($attributs as $attribute) {

            $vote  = $customerVoter->vote($mockTokenInterface, null, [$attribute]);
            self::assertNotEquals(VoterInterface::ACCESS_ABSTAIN, $vote);
        }
    }


        /**
     * @dataProvider provideRolesAndResult
     */
    public function testPolicyWithNoSubject(string $attribute, array $roles, int $resultatAttendu)
    {
        $customerVoter = new CustomerVoter();


        //given there is a moderator user
        $user = new User;
        $user->roles = $roles;

        $mockTokenInterface = $this->createMock(TokenInterface::class);
        $mockTokenInterface
        ->expects(self::once())
        ->method('getUser')
        ->willReturn($user);

       $result = $customerVoter->vote($mockTokenInterface, null, [$attribute]);

        self::assertEquals($resultatAttendu, $result);

        /*

         //given there is a adminuser
         $admin= new User;
         $admin->roles = ['ROLE_ADMIN'];
 
         $mockTokenInterface = $this->createMock(TokenInterface::class);
         $mockTokenInterface->expects(self::once())->method('getUser')->willReturn($admin);
 
        $result = $customerVoter->vote($mockTokenInterface, null, ['CAN_CREATE_CUSTOMER']);
 
         self::assertEquals($resultatAttendu, $result);
         *:

/*
         //given there is a normal user
         $user = new User;
 
         $mockTokenInterface = $this->createMock(TokenInterface::class);
         $mockTokenInterface->expects(self::once())->method('getUser')->willReturn($user);
 
        $result = $customerVoter->vote($mockTokenInterface, null, ['CAN_CREATE_CUSTOMER']);
 
         self::assertEquals(VoterInterface::ACCESS_GRANTED, $result);
          */
    }
   

    

    public function provideRolesAndResult() {
        return[
            [
                'CAN_CREATE_CUSTOMER',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_DENIED
            ],
            [
                'CAN_CREATE_CUSTOMER',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_CREATE_CUSTOMER',
                [],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_CUSTOMERS',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_CUSTOMERS',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_CUSTOMERS',
                [],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_ALL_CUSTOMERS',
                ['ROLE_MODERATOR'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_ALL_CUSTOMERS',
                ['ROLE_ADMIN'],
                VoterInterface::ACCESS_GRANTED
            ],
            [
                'CAN_LIST_ALL_CUSTOMERS',
                [],
                VoterInterface::ACCESS_DENIED
            ]
            ];
    }

    
    
    
    /**
     * @dataProvider generateNumbersAndResults
     */

     /*
    public function testAddition($x, $y, $resultatAttendu)
    {
        $resultat = $x + $y;

        $this->assertEquals($resultatAttendu, $resultat);
    }
    */
}