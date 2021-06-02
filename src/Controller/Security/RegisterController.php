<?php


namespace App\Controller\Security;


use DateTime;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            /*$hash = $encoder->encodePassword($user, $user->password);

            $user->password = $hash; */

            //$user->createdAt = new DateTime();

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('security_login');
        }


        return $this->render('security/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}