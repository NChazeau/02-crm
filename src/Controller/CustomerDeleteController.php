<?php

namespace  App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CustomerDeleteController extends AbstractController
{


    public function __construct(
        //CustomerRepository $customerRepository,
        EntityManagerInterface $em 
       )
        {
   // $this->repository = $customerRepository;
    $this->em = $em;

    }
    
    /**
     * @Route("/customers/{id}/delete", name="customers_delete")
     * @IsGranted("CAN_REMOVE", subject="customer")
     */
    
    public function delete(Customer $customer){

        /*if(!$this->isGranted('CAN_REMOVE', $customer)){
            throw new AccessDeniedException();
        }
        */
        /*
        if($customer->user !== $this->getUser()) {
            throw new NotFoundHttpException();
        }
        */
        
        /*
        //on a besoin de l'id ( j'ai besoin de la request)
        $id = $request->attributes->get('id');

        // On recup le customer existant ( j'ai besoin du customerRepository )
        $customer = $this->repository->find($id);
        // On vérifie qu'il existe ( 404 - je dois créer et retourner une réponse )
        if(!$customer){
            throw new NotFoundHttpException("Le client n°$id n'existe pas.");
        }
        */

        // On le suprime ( j'a ibesoin de l'eEntityManager)
        $this->em->remove($customer);
        $this->em->flush();

        //Redirection sur la liste ( Je dois créer et retourner une response 302)
    
        return $this->redirectToRoute('customers_list');
        
        /*$url = $this->urlGenerator->generate('customers_list');
        return $this->redirect($url);*/

        //Ancienes méthodes :
       // return new Response('', 302, ['Location' => $url]);
        //return new RedirectResponse($url);
        
    }
}