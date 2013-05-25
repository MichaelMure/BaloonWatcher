<?php

namespace lahaut\BaloonWatcherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BaloonWatcherBundle:Default:index.html.twig', array('name' => $name));
    }
}
