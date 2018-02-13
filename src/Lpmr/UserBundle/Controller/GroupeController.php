<?php

namespace Lpmr\UserBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Lpmr\UserBundle\Entity\Groupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Tests\Util\Validator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groupes = $em->getRepository('LpmrUserBundle:Groupe')->findAll();

        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
        ));
    }

    /**
     * Creates a new groupe entity.
     *
     */
    public function newAction(Request $request)
    {
        $chaine = ('0123456789');
        $code = str_shuffle(substr($chaine, 0, 8));
        $date = new \DateTime();;

        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('LpmrUserBundle:Groupe')->findOneBy(array('code' => $code));

        if($result == null){
          $groupe = new Groupe();
          $groupe->setAnnee($date);
          $groupe->setNbpointglobal(0);
          $groupe->setCode($code);
        }else{
          $code = str_shuffle(substr($chaine, 0, 8));
          $groupe = new Groupe();
          $groupe->setAnnee($date);
          $groupe->setNbpointglobal(0);
          $groupe->setCode($code);
        }


        $form = $this->createForm('Lpmr\UserBundle\Form\GroupeType', $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);

            $em->flush();

            return $this->redirectToRoute('groupe_show', array('id' => $groupe->getId()));
        }

        return $this->render('groupe/new.html.twig', array(
            'groupe' => $groupe,
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

            return $this->redirectToRoute('groupe_edit', array('id' => $groupe->getId()));
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

    public function getOneGroupe(Request $request){

     $content = $this->getContentAsArray($request);
     $em = $this->getDoctrine()->getManager();
     $groupe = $em->getRepository('LpmrUserBundle:Groupe')->findByCode($content->get('code'));

     return JsonResponse($groupe->getId());
     }

     protected function getContentAsArray(Request $request){
       $content = $request->getContent();

       if(empty($content)){
           throw new BadRequestHttpException("Content is empty");
       }

       if(!Validator::isValidJsonString($content)){
           throw new BadRequestHttpException("Content is not a valid json");
       }

       return new ArrayCollection(json_decode($content, true));
   }
}
