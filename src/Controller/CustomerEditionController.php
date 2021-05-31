<?php
namespace App\Controller;

use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerEditionController extends AbstractController {


    private $repository;
    private $em;

    public function __construct(CustomerRepository $customerRepository, EntityManagerInterface $em)
    {
        $this->repository = $customerRepository;
        $this->em = $em;
    }


    /**
     * @Route("/customers/{id}/edit", name="customers_edit")
     */

    public function edit(Request $request)
    {
    

        //1 Quel est l'id du customer à modifier ? (Request) 
        $id = $request->attributes->get('id');

        //2 Je vais chercher le  customer dans la base ( Customer Repository)
        $customer = $this->repository->find($id);
        //3 S'il n'existe pas, j'émet  une exception NotFound
        if (!$customer){
            throw new NotFoundHttpException("Le client n'a pas été trouvé");
        }

        //4 Créer le formulaire (FormFatoryInterface // $this->createForm)
        $form = $this->createForm(CustomerType::class);

        //BONUS : Gérer la soumission 
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){  
            // 1 Envoyer la requête ( EntityManagerInterface)
            $this->em->flush();

            //2 Rediriger vers la liste des customers ( Router Interface , URMG GeneratorInterface)
            return $this->redirectToRoute("customers_list");
        }

        //5 Afficher un fichier Twig (Environnement // $this->render)
        return $this->render("customers/edit.html.twig", [
            'form' => $form->createView()
        ]);

    }
}

