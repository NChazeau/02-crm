<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CustomerListController extends AbstractController
{

    private $repository;
    //private $twig;

    public function __construct(CustomerRepository $customerRepository 
    //Environment $twig
    )
    {
        $this->repository = $customerRepository;
        //$this->twig = $twig;
    }


/**
 * @Route("/clients", name="customers_list")
 */

 public function list(): Response
 {
    $customers = $this->repository->findAll();


    return $this->render("customers/list.html.twig", ['customers' => $customers]);

    //Avec le  précédent return, on a plus besoin de twig : 
    /*
    $html = $this->twig->render("customers/list.html.twig", ['customers' => $customers]);
    

    return new Response($html);
    */
 }

}



