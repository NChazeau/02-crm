<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */

class Customer {
   
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */

    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(min=3, minMessage="Le prénom doit faire 3 caractères minimum")
     */

    public string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom doit faire 3 caractères minimum")
     */

    public string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le mail est obligatoire")
     * @Assert\Email(message="mail mal formaté")
     */

    public string $email;

    public function getFullName(): string
    {
        return "$this->firstName $this->lastName";
    }


}