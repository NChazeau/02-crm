<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CustomerCreationController extends AbstractController
{
    private $em;
    private $validator;
    private $formFactory;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validatorInterface,
        FormFactoryInterface $formFactoryInterface
    ) {
        $this->em = $em;
        $this->validator = $validatorInterface;
        $this->formFactory = $formFactoryInterface;
    }

    /**
     * @Route("/customers/create", name="customers_create")
     * @return Response 
     */
    public function displayForm(Request $request, FlashBagInterface $flashbag): Response
    {
        
        $form = $this->createForm(CustomerType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();

            $this->em->persist($customer);
            $this->em->flush();

            /** @var FlashBagInterface */
           // $flashBag = $request->getSession()->getBag('flashes');
           $this->addFlash('success', 'le client a bien été enregistré');
           // $flashBag->add('success', 'Le client a bien été enregistré');
            //$flashBag->add('danger', 'Le client est un plouc');
            //$flashBag->add('success', 'Bravo tout s\'est bien passé');

            return $this->redirectToRoute("customers_list");
        }

        return $this->render('customers/create.html.twig', [
            'form' => $form->createView()
        ]);

        
    }

















}

