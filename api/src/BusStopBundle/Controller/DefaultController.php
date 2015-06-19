<?php namespace BusStopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    public function getLineTypesAction(Request $Request)
    {

        #if($Request->isXmlHttpRequest()) {
	        $em = $this->getDoctrine()->getManager();
	        $repository = $em->getRepository('BusStopBundle:Line');
	        
	        foreach( $repository->findAll() as $line ) {
	            $lines[ $line->getLinetype()->getName() ][] = array( 'lineNumber'=> $line->getId() );
	        }

        return new JsonResponse( $lines );
    }

    public function getMessageAction(Request $Request)
    {
        	$dayLimit = ( $Request->request->get('dayLimit') ? $Request->request->get('dayLimit') : 7);
        	$queryLimit = ( $Request->request->get('queryLimit') ? $Request->request->get('queryLimit') : 5);

    		$date = new \DateTime();
    		$date->modify("-$dayLimit day");

			$repository = $this->getDoctrine()
			    ->getRepository('BusStopBundle:Message');

			$query = $repository->createQueryBuilder('m')
			    ->where('m.date > :date')
			    ->setParameter('date', $date)
			    ->orderBy('m.date', 'ASC')
			    ->setMaxResults($queryLimit)
			    ->getQuery();

	        
	        foreach( $query->getResult() as $msg ) {
	            $messages[ $msg->getDate() ][] = array(
	            	'short' => $msg->getMessageShort(),
	            	'long'  => $msg->getMessageFull()
	            );
	        }

        return new JsonResponse( $messages );
    }
}
