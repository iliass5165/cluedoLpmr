<?php

namespace Lpmr\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LpmrAppBundle:Default:index.html.twig');
    }
}
