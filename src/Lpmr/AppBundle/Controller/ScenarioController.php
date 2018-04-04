<?php

namespace Lpmr\AppBundle\Controller;

use Lpmr\AppBundle\Entity\Scenario;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $elements = $em->getRepository('LpmrAppBundle:Element')->findAll();

        return $this->render('LpmrAppBundle:Scenario:index.html.twig', array(
            'scenarios' => $scenarios,
            'elements' => $elements,
        ));
    }

    /**
     * Creates a new scenario entity.
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $scenario = new Scenario();
        $elements = $em->getRepository('LpmrAppBundle:Element')->findAll();

        $form = $this->createForm('Lpmr\AppBundle\Form\ScenarioType', $scenario);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $em->persist($scenario);
            $em->flush();

          
            return $this->redirectToRoute("scenario_index");
        }

        return $this->render('LpmrAppBundle:Scenario:new.html.twig', array(
            'scenario' => $scenario,
            'elements' => $elements,
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


    public function setSelectedScenarioAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        $content = $request->getContent();
        $jsonContent = json_decode($content);
        if($jsonContent->selectedId != null)
        {
            $em = $this->getDoctrine()->getManager();
            $scenarios = $em->getRepository('LpmrAppBundle:Scenario')->findBySelectedScenario(1);
        
            if(count($scenarios) > 0)
            {
                foreach($scenarios as $sc)
                {
                    $sc->setSelectedScenario(0);
                    $em->persist($sc);
                }
                
            }
            $scenario = $em->getRepository('LpmrAppBundle:Scenario')->find($jsonContent->selectedId);
            $scenario->setSelectedScenario(1);
            $em->persist($scenario);
            $em->flush();
            $object = [];
            if(count($scenario->getFkElement()) > 0)
            {
                $crimeElements = [];
                $elementArray = [];
                foreach($scenario->getFkElement() as $element)
                {
                    if($element->getUsedInCrime()){
                        $crimeElements[] = $element->getId();
                    }
                    $elementArray[] = $element->getId();
                    
                }
               
                $object["elements"] = $elementArray;
                $object["crimeElements"] = $crimeElements;
                return new JsonResponse($object, 200);
            }
            

            return new JsonResponse(["status" => "success"]);
        }
        
    }


    public function addCheckedElementToScenarioAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        //json: {'scenario':":id", 'element': ":id"}
        $content = $request->getContent();
        $jsonContent = json_decode($content);
        if($jsonContent != null)
        {
            $em = $this->getDoctrine()->getManager();
            $scenario = $em->getRepository("LpmrAppBundle:Scenario")->find($jsonContent->scenario);
            $element = $em->getRepository("LpmrAppBundle:Element")->find($jsonContent->element);
            $element->addFkScenario($scenario);
            $em->persist($element);
            $em->flush();
            return new JsonResponse(["status" => "added"]);
        }
        return new JsonResponse(["status" => "Error"]);
    }

    public function removeCheckedElementToScenarioAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }
        //json: {'scenario':":id", 'element': ":id"}
        $content = $request->getContent();
        $jsonContent = json_decode($content);
        if($jsonContent != null)
        {
            $em = $this->getDoctrine()->getManager();
            $scenario = $em->getRepository("LpmrAppBundle:Scenario")->find($jsonContent->scenario);
            $element = $em->getRepository("LpmrAppBundle:Element")->find($jsonContent->element);
            $element->removeFkScenario($scenario);
            $em->persist($element);
            $em->flush();
            return new JsonResponse(["status" => "removed"]);
        }
        return new JsonResponse(["status" => "Error"]);
    }


    public function getSelectedElementsOfSelectedScenarioAction(Request $request)
    {
        // if (!$request->isXmlHttpRequest()) {
        //     return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        // }
        //json {"scenario": ":id"}
        $content = $request->getContent();
        $jsonContent = json_decode($content);
        if($jsonContent != null)
        {
            $em = $this->getDoctrine()->getManager();
            $scenario = $em->getRepository("LpmrAppBundle:Scenario")->find($jsonContent->scenario);
            $object = [];
            
            
            
            if(count($scenario->getFkElement()) > 0)
            {   
                $crimeElements = [];
                $elementArray = [];
                foreach($scenario->getFkElement() as $element)
                {
                    if($element->getUsedInCrime()){
                        $crimeElements[] = $element->getId();
                    }
                    $elementArray[] = $element->getId();
                    
                }
                
                $object["elements"] = $elementArray;
                $object["crimeElements"] = $crimeElements;
                return new JsonResponse($object, 200);
            }
            else
            {
                return new JsonResponse(["elements" => null, "crimeElements" => null ], 200);
            }
            
            
        }
        return new JsonResponse(["status" => "Error"], 500);
    }

    public function getQrCodesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $indices = ($em->getRepository('LpmrAppBundle:Scenario')->findBySelectedScenario(1)[0])->getFkElement();
        
        if(count($indices) > 0)
        {
            $qrcodes = [];
            foreach($indices as $indice)
            {
                $qrcodes[] = $indice->getUrl();
            }
        }
        return new Response(dump($qrcodes));
    }
    
    
}
