<?php

namespace BusStopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BusStopBundle:Default:index.html.twig', array('name' => $name));
    }
}
