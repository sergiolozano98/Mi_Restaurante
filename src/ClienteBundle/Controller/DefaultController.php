<?php

namespace ClienteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClienteBundle\Entity\Cliente;
use ClienteBundle\Form\ClienteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('ClienteBundle:Default:index.html.twig');
    }

    /**
     * @Route("/registro", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new Cliente();
        $form = $this->createForm(ClienteType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
            ->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return new Response("Usuario insertado correctamente");
        }

        return $this->render(
            'ClienteBundle:Default:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
