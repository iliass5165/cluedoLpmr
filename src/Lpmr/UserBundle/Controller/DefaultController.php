<?php

namespace Lpmr\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LpmrUserBundle:Default:index.html.twig');
    }
}
