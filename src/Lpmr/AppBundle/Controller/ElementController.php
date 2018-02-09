<?php

namespace Lpmr\AppBundle\Controller;

use Lpmr\AppBundle\Entity\Element;
use Lpmr\AppBundle\Entity\CategorieElement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}
