<?php

namespace Lpmr\AppBundle\Controller;

use Lpmr\AppBundle\Entity\Element;
use Lpmr\AppBundle\Entity\CategorieElement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Element controller.
 *
 */
class ElementController extends Controller
{
    /**
     * Lists all element entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $elements = $em->getRepository('LpmrAppBundle:Element')->findAll();

        return $this->render('element/index.html.twig', array(
            'elements' => $elements,
        ));
    }

    /**
     * Creates a new element entity.
     *
     */
    public function newAction(Request $request, CategorieElement $fkid)
    {
        $element = new Element();
        $form = $this->createForm('Lpmr\AppBundle\Form\ElementType', $element->setFkCategorieElement($fkid));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($element);
            $em->flush();

            return $this->redirectToRoute('element_show', array('id' => $element->getId()));
        }

        return $this->render('element/new.html.twig', array(
            'element' => $element,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a element entity.
     *
     */
    public function showAction(Element $element)
    {
        $deleteForm = $this->createDeleteForm($element);

        return $this->render('element/show.html.twig', array(
            'element' => $element,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing element entity.
     *
     */
    public function editAction(Request $request, Element $element)
    {
        $deleteForm = $this->createDeleteForm($element);
        $editForm = $this->createForm('Lpmr\AppBundle\Form\ElementType', $element);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('element_edit', array('id' => $element->getId()));
        }

        return $this->render('element/edit.html.twig', array(
            'element' => $element,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a element entity.
     *
     */
    public function deleteAction(Request $request, Element $element)
    {
        $form = $this->createDeleteForm($element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($element);
            $em->flush();
        }

        return $this->redirectToRoute('scenario_index');
    }

    /**
     * Creates a form to delete a element entity.
     *
     * @param Element $element The element entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Element $element)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('element_delete', array('id' => $element->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
























    public function getElementsAction(){

     $em = $this->getDoctrine()->getManager();
     $elements = new Element();
     $elements = $em->getRepository('LpmrAppBundle:Element')->findBy(array('fkCategorieElement' => 1));
     $armes = $elements;

     $elements = new Element();
     $elements = $em->getRepository('LpmrAppBundle:Element')->findBy(array('fkCategorieElement' => 2));
     $lieux = $elements;

     $elements = new Element();
     $elements = $em->getRepository('LpmrAppBundle:Element')->findBy(array('fkCategorieElement' => 2));
     $personnages = $elements;

     $api = array();
     array_push($api, 'armes', $armes);
     array_push($api, 'lieux', $lieux);
     array_push($api, 'personnages', $personnages);
     var_dump($api);

     $encoder = new JsonEncoder();
     $normalizer = new GetSetMethodNormalizer();

     $serializer = new Serializer(array($normalizer), array($encoder));

     $jsonContent = $serializer->serialize($api, 'json');

     return new JsonResponse($jsonContent);


     }

     protected function getContentAsArray(Request $request){
       $content = $request->getContent();

       if(empty($content)){
           throw new BadRequestHttpException("Content is empty");
       }
       return new ArrayCollection(json_decode($content, true));
   }
}
