<?php

namespace Lpmr\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Lpmr\UserBundle\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Groupe controller.
 *
 */
class GroupeController extends Controller
{
    /**
     * Lists all groupe entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $groupes = $em->getRepository('LpmrUserBundle:Groupe')->findAll();
        $students = $em->getRepository("LpmrUserBundle:Etudiant")->findAll();
        $form = $this->createFormBuilder()
        ->add('nbStudents', NumberType::class)
        ->add('generate', SubmitType::class)
        ->setAction($this->generateUrl('groupe_index'))
        ->getForm()
        ;
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            return var_dump($form->getData());
        }


        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
            'students' => $students,
            "formGenerate" => $form->createView()
        ));
    }

    /**
     * Creates a new groupe entity.
     *
     */
    public function newAction(Request $request)
    {
        $groupe = new Groupe();
        $form = $this->createForm('Lpmr\UserBundle\Form\GroupeType', $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $result = 1;
            while($result != null)
            {
                $code = str_shuffle(substr("0123456789", 0, 8));
                $result = $em->getRepository('LpmrUserBundle:Groupe')->findOneBy(array('code' => $code));
            }            
            $date = new \DateTime();
            $groupe->setAnnee($date);
            $groupe->setNbpointglobal(0);
            $groupe->setCode($code);
            //verification si l'admin n'a pas ajouter des etudiant manullement
            if(count($groupe->getEtudiants()) == 0)
            {
                //recuperation des etudiant qui ont pas de groupe
                $students = $em->getRepository("LpmrUserBundle:Etudiant")->findByGroupe(null);
                
                foreach($students as $students)
                {
                    
                }
                
            }




            $em->persist($groupe);
            $em->flush();

            return $this->redirectToRoute('groupe_index', array('id' => $groupe->getId()));
        }

        return $this->render('groupe/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a groupe entity.
     *
     */
    public function showAction(Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);

        return $this->render('groupe/show.html.twig', array(
            'groupe' => $groupe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing groupe entity.
     *
     */
    public function editAction(Request $request, Groupe $groupe)
    {
        $deleteForm = $this->createDeleteForm($groupe);
        $editForm = $this->createForm('Lpmr\UserBundle\Form\GroupeType', $groupe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groupe_index', array('id' => $groupe->getId()));
        }

        return $this->render('groupe/edit.html.twig', array(
            'groupe' => $groupe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a groupe entity.
     *
     */
    public function deleteAction(Request $request, Groupe $groupe)
    {
        $form = $this->createDeleteForm($groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupe);
            $em->flush();
        }

        return $this->redirectToRoute('groupe_index');
    }

    /**
     * Creates a form to delete a groupe entity.
     *
     * @param Groupe $groupe The groupe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Groupe $groupe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groupe_delete', array('id' => $groupe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function getOneGroupeAction(Request $request){

     $content = $this->getContentAsArray($request);
     $em = $this->getDoctrine()->getManager();
     $groupe = new Groupe();
     $groupe = $em->getRepository('LpmrUserBundle:Groupe')->findOneBy(array('code' => $content->get('code')));

     if($groupe != null){
       $chaine = ('abcdefghijklmnopqrstuvwxyz0123456789');
       $token = str_shuffle(substr($chaine, 0, 32));
       if($content->get('token') == null ){
         $groupe->setToken($token);
         $em->persist($groupe);
         $em->flush();
       }

       return new JsonResponse($groupe->getToken());
     }
       return new JsonResponse(null);


     }

     protected function getContentAsArray(Request $request){
       $content = $request->getContent();

       if(empty($content)){
           throw new BadRequestHttpException("Content is empty");
       }
       return new ArrayCollection(json_decode($content, true));
   }
}
