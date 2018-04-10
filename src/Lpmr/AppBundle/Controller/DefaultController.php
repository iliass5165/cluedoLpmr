<?php

namespace Lpmr\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;



class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LpmrAppBundle:Default:index.html.twig');
    }

    public function getApkAction(){
        // return 
        $apk = new BinaryFileResponse($this->getParameter('apk_dir')."enquete-lpmr.apk");
        $apk->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'enquête-lpmr.apk'
        );
        return $apk;
        
    }
}
