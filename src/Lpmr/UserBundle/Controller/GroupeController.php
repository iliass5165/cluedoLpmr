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
        ->add('nbStudents', NumberType::class, [
            'label' => "test"
        ])
        ->add('generate', SubmitType::class)
        ->setAction($this->generateUrl('groupe_index'))
        ->getForm()
        ;
        $studentsWithoutGroup  = [];
        foreach($students as $student)
        {
            if(is_null($student->getGroupe())){
                $studentsWithoutGroup[] = $student;
            }
        }
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $nbStudents = $form->getData()['nbStudents'];    
            $groupeNum = 1;
            if(count($groupes) > 0)
            {
                $groupeNum = (end($groupes)->getId()) + 1;
            }

            while(count($studentsWithoutGroup) >= $nbStudents) 
            {
                $code = rand(11111111, 99999999);
                while($em->getRepository("LpmrUserBundle:Groupe")->findByCode($code))
                {
                    $code = rand(11111111, 99999999);
                }
                $groupe = new Groupe();
                $groupe->setNom("groupe".$groupeNum);
                $groupe->setCode($code);
                $groupe->setNbpointglobal(0);
                $groupe->setAnnee(new \DateTime());
                $randStudents = array_rand($studentsWithoutGroup, $nbStudents);
                
                foreach($randStudents as $randStudent)
                {
                    $groupe->addEtudiant($studentsWithoutGroup[$randStudent]);
                    unset($studentsWithoutGroup[$randStudent]);
                    
                }

                 $em->persist($groupe);
                $groupeNum++;
            }
                
            if(count($studentsWithoutGroup) > 0)
            {
                $code = rand(11111111, 99999999);
                while($em->getRepository("LpmrUserBundle:Groupe")->findByCode($code))
                {
                    $code = rand(11111111, 99999999);
                }
                
                $groupe = new Groupe();
                $groupe->setNom("groupe".$groupeNum);
                $groupe->setCode($code);
                $groupe->setNbpointglobal(0);
                $groupe->setAnnee(new \DateTime());
                foreach($studentsWithoutGroup as $student)
                {
                    $groupe->addEtudiant($student);  
                    unset($studentsWithoutGroup[$randStudent]);                  
                }
                $em->persist($groupe);
                $groupeNum++;
            }
          
            $em->flush();
            return $this->redirectToRoute('groupe_index');
        }

        
        return $this->render('groupe/index.html.twig', array(
            'groupes' => $groupes,
            'students' => $students,
            "studentsWithoutGroup" => $studentsWithoutGroup,
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
                $code = rand(11111111, 99999999);
                $result = $em->getRepository('LpmrUserBundle:Groupe')->findOneBy(array('code' => $code));
            }            
            $date = new \DateTime();
            $groupe->setAnnee($date);
            $groupe->setNbpointglobal(0);
            $groupe->setCode($code);
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            // die(dump($groupe));
            $em->flush();

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
    * View all groups in home
    *
    */
    public function homeAction(){
      $em = $this->getDoctrine()->getManager();

      $groupes = $em->getRepository('LpmrUserBundle:Groupe')->findAll();

      return $this->render('groupe/home.html.twig', array(
          'groupes' => $groupes,
      ));
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
        //if/else ajax
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $content = $request->getContent();
        if($content)
        {
            $jsonContent = json_decode($content);
            if($jsonContent->code)
            {
                $em = $this->getDoctrine()->getManager();
                $groupe = $em->getRepository("LpmrUserBundle:Groupe")->findByCode($jsonContent->code);
                if(count($groupe) > 0)
                {
                    $groupe = $groupe[0];
                    if(!$groupe->getToken() || ($groupe->getToken() != null && $jsonContent->token == null))
                    {
                        $groupe->setToken(bin2hex(random_bytes(10)));
                        $em->persist($groupe);
                        $em->flush();
                        $response = json_encode(["code"=>$groupe->getCode(), "token"=>$groupe->getToken()]);
                        return new Response($response, 200, [
                            "Content-Type" => "application/json"
                        ]);
                    }
                }
                else
                {
                    return new JsonResponse(["error" => "groupe invalide"], 403, [
                        "Content-Type" => "application/json"
                    ]);
                }
                
            }
            return new JsonResponse(["error" => "code invalide"], 404, [
                "Content-Type" => "application/json"
            ]);
        }

        return new JsonResponse(["error" => "requête invalide "], 404, [
            "Content-Type" => "application/json"
        ]);
     }

     protected function getContentAsArray(Request $request){
       $content = $request->getContent();

       if(empty($content)){
           throw new BadRequestHttpException("Content is empty");
       }
       return new ArrayCollection(json_decode($content, true));
   }
}
