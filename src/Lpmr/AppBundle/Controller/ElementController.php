<?php

namespace Lpmr\AppBundle\Controller;

use Lpmr\AppBundle\Entity\Element;
use Lpmr\AppBundle\Entity\CategorieElement;
use Lpmr\AppBundle\Entity\GroupeElements;
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
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $element = new Element();
        $form = $this->createForm('Lpmr\AppBundle\Form\ElementType', $element);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $element->setUsedInCrime(false);
            $file = $element->getUrl();
            $fileType = explode("/",$file->getMimeType())[0];
            if($fileType == "image")
            {
                $extension = $file->guessExtension();
                $filename = md5(uniqid()).".".$extension;
                $file->move(
                    $this->getParameter('upload_dir'),
                    $filename
                );
                $element->setUrl($filename);
            }
            elseif($fileType == "video")
            {
                //force to mp4
                $filename = md5(uniqid()).".mp4";
                $file->move(
                    $this->getParameter('upload_dir'),
                    $filename
                );
                $element->setUrl($filename);
            }
            else
            {
                new UnsupportedMediaTypeHttpException("type of uploaded ressource invalide!");
            }
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
        $url = $element->getUrl();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $element->getUrl();
            if($file)
            {
                $fileType = explode("/",$file->getMimeType())[0];
                if($fileType == "image")
            
                {
                    $extension = $file->guessExtension();
                    $filename = md5(uniqid()).".".$extension;
                    $file->move(
                        $this->getParameter('upload_dir'),
                        $filename
                    );
                    $element->setUrl($filename);
                }
                elseif($fileType == "video")
                {
                    //force to mp4
                    $filename = md5(uniqid()).".mp4";
                    $file->move(
                        $this->getParameter('upload_dir'),
                        $filename
                    );
                    $element->setUrl($filename);
                }
                else
                {
                    new UnsupportedMediaTypeHttpException("type of uploaded ressource invalide!");
                }
            }
            else
            {
                $element->setUrl($url);
            }

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


    public function getIndiceAction($name)
    {
        //be sure it'is an AJAX call
        // be sure of the Goupe TOken
        
        $response = new BinaryFileResponse($this->getParameter('upload_dir').$name);
        return $response;
    }

    public function setCrimeElementsAction(Request $request){
        // if (!$request->isXmlHttpRequest()) {
        //     return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        // }

        $jsonContent = json_decode($request->getContent());
        
        
        if($jsonContent ==! null){
            
            $em = $this->getDoctrine()->getManager();
            $element = $em->getRepository("LpmrAppBundle:Element")->find($jsonContent->selectedId);
            if($element == null){
                return new JsonResponse(["status" => "Error no element found"]);
            }
            
            $otherElemenents = $em->getRepository("LpmrAppBundle:Element")->findByFkCategorieElement($element->getFkCategorieElement());
            if(count($otherElemenents) > 0)
            {
                foreach($otherElemenents as $ele){
                    $ele->setUsedInCrime(false);
                    $em->persist($ele);
                }
            }
            

            $element->setUsedInCrime(true);
            $em->persist($element);
            $em->flush();
            return new JsonResponse(["status" => "done"]);

            
        }
        return new JsonResponse(["status" => "Error"]);

    }




















    public function getElementsAction(Request $request){
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $em = $this->getDoctrine()->getManager();
        $scenario = $em->getRepository("LpmrAppBundle:Scenario")->findOneBySelectedScenario(1);
        $elements = $scenario->getFkElement();
        
        if(count($elements) > 0)
        {
            $arrayOfElements = [];
            foreach($elements as $element)
            {
                if($element->getFkCategorieElement()->getNom() != "Faux indices")
                {    
                    $object = ["id" => $element->getId(),"name" => $element->getNom(), "category" => $element->getFkCategorieElement()->getNom()];
                    $arrayOfElements[] = $object;
                }
            }
            return new JsonResponse($arrayOfElements);
        }
        else
        {
            return new JsonResponse(["error" => "Empty data"]);
        }
   }

   public function postElementsAction(Request $request){
    if (!$request->isXmlHttpRequest()) {
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    }
    $jsonContent = json_decode($request->getContent());
    if($jsonContent != null)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository("LpmrUserBundle:Groupe")->findOneByCode($jsonContent->code);
        if($jsonContent->token != $groupe->getToken()){
            return new JsonResponse(["status" => "wrong token"], 401);  
        }
        if($jsonContent->lock)
        {
            $groupe->setActivated(false);
            $em->persist($groupe);
        }
        foreach($jsonContent->clues as $element){
            $anElement = $em->getRepository("LpmrAppBundle:Element")->find($element->id);
            

            $groupeElements = $em->getRepository("LpmrAppBundle:GroupeElements")->findOneBy(["groupe" => $groupe, "element" => $anElement]);
            if($groupeElements != null)
            {
                $groupeElements->setSelected($element->checked);
            }
            else
            {
                $groupeElements = new GroupeElements();
                $groupeElements->setSelected($element->checked);
                $groupeElements->setElement($anElement);
                $groupeElements->setGroupe($groupe);
            }
            $em->persist($groupeElements);
        }

        $em->flush();
        return new JsonResponse(["status" => "Done"], 200);
    }

    return new JsonResponse(["status" => "Erreur lors de la récupération du contenu #ptEls"], 500);
   }

   public function postScannedElementAction(Request $request){
    if (!$request->isXmlHttpRequest()) {
        return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
    }
    $jsonContent = json_decode($request->getContent());
    
    if($jsonContent != null)
    {
 
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository("LpmrUserBundle:Groupe")->findOneByCode($jsonContent->code);
        $element = $em->getRepository("LpmrAppBundle:Element")->findOneByUrl($jsonContent->ressource);
        
        if($groupe == null || $element == null){
            return new JsonResponse(["status" => " Element ou groupe non valide !"], 500);
        }
        else if($groupe->getToken() != $jsonContent->token){
            return new JsonResponse(['status' => "wrong token"], 403);
        }
        else
        {
            
            $groupeElements = $em->getRepository("LpmrAppBundle:GroupeElements")->findOneBy(["groupe" => $groupe, "element" => $element]);
            
            

            if($groupeElements != null)
            {
                
                if($groupeElements->getScanned() == null){
                    $groupeElements->setScanned(true);    
                    $groupe->setNbPointGlobal($groupe->getNbPointGlobal() + 10);
                }
                else
                {
                    //diminuer les points
                    $groupe->setNbPointGlobal($groupe->getNbPointGlobal() - 10);
                }
            }
            else
            {
                $groupe->setNbPointGlobal($groupe->getNbPointGlobal() + 10);
                $groupeElements = new GroupeElements();
                $groupeElements->setScanned(true);
                $groupeElements->setElement($element);
                $groupeElements->setGroupe($groupe);
            }
            
            $em->persist($groupeElements);
            $em->flush();
            return new JsonResponse(["status" => "done"], 200);
        }
        

      
    }
    return new JsonResponse(["status" => "Erreur lors de la récupération du contenu #ptScEls"], 500);
   }
}
