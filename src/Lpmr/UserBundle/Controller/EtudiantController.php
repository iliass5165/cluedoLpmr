<?php

namespace Lpmr\UserBundle\Controller;

use Lpmr\UserBundle\Entity\Etudiant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


/**
 * Etudiant controller.
 *
 */
class EtudiantController extends Controller
{
    /**
     * Lists all etudiant entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $etudiants = $em->getRepository('LpmrUserBundle:Etudiant')->findAll();

        $form = $this->createFormBuilder()
        ->add('submitFile', FileType::class, ['label' => 'Importer', 'attr'=>['accept-charset'=>'UTF-8']])
         ->add('save', SubmitType::class, ['attr' => ['class' => 'save']])
         ->getForm()
        ;

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $filename = $form->get('submitFile')->getData()->getPathName();
            $data = $this->csvToArray($filename);
            foreach($data as $line)
            {
                //nom;prenom;promotion
                $etudiant = new Etudiant();
                $etudiant->setNom($line[0]);
                $etudiant->setPrenom($line[1]);
                $etudiant->setPromotion($line[2]);
                $em->persist($etudiant);
                
            }
            $em->flush();
            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('LpmrUserBundle:etudiant:index.html.twig', array(
            'etudiants' => $etudiants,
            "form" => $form->createView()
        ));
    }

    /**
     * Creates a new etudiant entity.
     *
     */
    public function newAction(Request $request)
    {
        $etudiant = new Etudiant();
        $form = $this->createForm('Lpmr\UserBundle\Form\EtudiantType', $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();

            return $this->redirectToRoute('etudiant_show', array('id' => $etudiant->getId()));
        }

        return $this->render('LpmrUserBundle:etudiant:new.html.twig', array(
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a etudiant entity.
     *
     */
    public function showAction(Etudiant $etudiant)
    {
        $deleteForm = $this->createDeleteForm($etudiant);

        return $this->render('LpmrUserBundle:etudiant:show.html.twig', array(
            'etudiant' => $etudiant,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing etudiant entity.
     *
     */
    public function editAction(Request $request, Etudiant $etudiant)
    {
        $deleteForm = $this->createDeleteForm($etudiant);
        $editForm = $this->createForm('Lpmr\UserBundle\Form\EtudiantType', $etudiant);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etudiant_edit', array('id' => $etudiant->getId()));
        }

        return $this->render('LpmrUserBundle:etudiant:edit.html.twig', array(
            'etudiant' => $etudiant,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a etudiant entity.
     *
     */
    public function deleteAction(Request $request, Etudiant $etudiant)
    {
        $form = $this->createDeleteForm($etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($etudiant);
            $em->flush();
        }

        return $this->redirectToRoute('etudiant_index');
    }

    /**
     * Creates a form to delete a etudiant entity.
     *
     * @param Etudiant $etudiant The etudiant entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Etudiant $etudiant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('etudiant_delete', array('id' => $etudiant->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



    private function csvToArray($fileName)
    {
      $csv = array();

      if (($file = fopen($fileName, 'r')) === false) {
          throw new Exception('There was an error loading the CSV file.');
      } else {
         while (($line = fgetcsv($file, 1000, ';')) !== false) {
            $line = array_map("utf8_encode", $line);
            $csv[] = $line;
         }
         fclose($file);
      }
      return $csv;
    }
}
