<?php namespace BusStopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    public function getLineTypesAction(Request $Request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BusStopBundle:Line');
        
        foreach( $repository->findAll() as $line ) {
            $lines[ $line->getLinetype()->getName() ][] = array( 'lineNumber'=> $line->getId() );
        }
        

        return new JsonResponse( $lines );
    }
}
