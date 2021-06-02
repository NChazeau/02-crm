<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\CustomerRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerListController extends AbstractController
{

    private $repository;
    //private $twig;
    private $security;

    public function __construct(CustomerRepository $customerRepository, Security $security 
    //Environment $twig
    )
    {
        $this->repository = $customerRepository;
        //$this->twig = $twig;
        //$this->security = $security;
    }


/**
 * @Route("/customers", name="customers_list")
 * @IsGranted("CAN_LIST_CUSTOMERS")
 */

 public function list(): Response
 {
    
    
/*
    if(!$this->isGranted('CAN_LIST_CUSTOMERS')){
        throw new AccessDeniedException();
    }
    */
    /*
    if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MODERATOR')) {
            $customers = $this->repository->findAll();
        } else {
            $customers = $this->repository->findBy(['user' => $this->getUser()]);
        }
    */

    if ($this->isGranted('CAN_LIST_ALL_CUSTOMERS')) {
        $customers = $this->repository->findAll();
    } else {
        $customers = $this->repository->findBy(['user' => $this->getUser()]);
    }



    return $this->render("customers/list.html.twig", ['customers' => $customers]);

    //Avec le  précédent return, on a plus besoin de twig : 
    /*
    $html = $this->twig->render("customers/list.html.twig", ['customers' => $customers]);
    

    return new Response($html);
    */
 }

}



