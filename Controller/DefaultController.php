<?php

namespace Yan\Bundle\SitePlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SitePlatformBundle:Default:index.html.twig', array('name' => $name));
    }
}
