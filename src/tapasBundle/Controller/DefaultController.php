<?php

namespace tapasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use tapasBundle\Entity\Tapas;
use tapasBundle\Form\TapasType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="inicio")
     */
    public function indexAction()
    {
      //replace this example code with whatever you need
      $repository = $this->getDoctrine()->getRepository(Tapas::class);
      // find *all* jugadores
      $tapas = $repository->findAll();
      return $this->render('tapasBundle:Default:index.html.twig',array("AllTapas"=>$tapas));
    }

    /**
     * @Route("/form", name="insertar")
     */
    public function TapasFormAction(Request $request)
    {
         $tapas = new Tapas();
         $form = $this->createForm(TapasType::Class);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
         // $form->getData() holds the submitted values
         // but, the original `$task` variable has also been updated
         $tapas = $form->getData();

         // ... perform some action, such as saving the task to the database
         // for example, if Task is a Doctrine entity, save it!
          $em = $this->getDoctrine()->getManager();
          $em->persist($tapas);
          $em->flush();

           return $this->redirectToRoute('inicio');
   }
         //le pasamos a la vista el formulario ya pintado
         return $this->render('tapasBundle:Default:formulario.html.twig',array("form"=>$form->createView() ));
    }
    /**
     * @Route("/borrarformtapas/{id}",name="borrartapas")
     */
    public function borrarFormtop100( Request $request,$id)
    {
      $tapa = $this->getDoctrine()->getRepository('tapasBundle:Tapas')->find($id);
      $form=$this->createForm(TapasType::Class, $tapa);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
         {
             $em = $this->getDoctrine()->getManager();
             $em->remove($tapa);
             $em->flush();
             return $this->redirectToRoute('inicio');
         }
           return $this->render("tapasBundle:Default:formulario.html.twig", array('form'=>$form->createView() ));
     }

}
