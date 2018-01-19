<?php

namespace Lpmr\AppBundle\Controller;

use Lpmr\AppBundle\Entity\Scenario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScenarioController extends Controller
{
    /**
     * View all scenarios
     */
    public function indexAction()
    {   
        $em = $this->getDoctrine()->getManager();

        $scenarios = $em->getRepository('LpmrAppBundle:Scenario')->findAll();

        return $this->render('LpmrAppBundle:Scenario:index.html.twig', array(
            'scenarios' => $scenarios,
        ));
    }

    /**
     * Creates a new scenario entity.
     */
    public function newAction(Request $request)
    {
        $scenario = new Scenario();
        $form = $this->createForm('Lpmr\AppBundle\Form\ScenarioType', $scenario);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();          
            
            $em->persist($scenario);
            $em->flush();
            
            return $this->redirectToRoute('scenario_show', array('id' => $scenario->getId()));
        }
        
        return $this->render('LpmrAppBundle:Scenario:new.html.twig', array(
            'scenario' => $scenario,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show selected scenario
     */
    public function showAction(Scenario $scenario)
    {
        $deleteForm = $this->createDeleteForm($scenario);

        return $this->render('LpmrAppBundle:Scenario:show.html.twig', array(
            'scenario' => $scenario,
            'delete_form' => $deleteForm->createView(), 
        ));
    }

    /**
     * Edit selected scenario
     */
    public function editAction(Request $request, Scenario $scenario)
    {      
        $deleteForm = $this->createDeleteForm($scenario);
        $editForm = $this->createForm('Lpmr\AppBundle\Form\ScenarioType', $scenario);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('scenario_edit', array('id' => $scenario->getId()));
        }

        return $this->render('LpmrAppBundle:Scenario:edit.html.twig', array(
            'scenario' => $scenario,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete selected scenario
     */
    public function deleteAction(Request $request, Scenario $scenario)
    {
        $form = $this->createDeleteForm($scenario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($scenario);
            $em->flush();
        }

        return $this->redirectToRoute('scenario_index');
    }
    
    /**
     * Creates a form to delete a scenario entity.
     *
     * @param Scenario $scenario The scenario entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Scenario $scenario)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('scenario_delete', array('id' => $scenario->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
