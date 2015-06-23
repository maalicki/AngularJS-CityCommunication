<?php namespace BusStopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
	public function getBusStopNameAction(Request $Request)
	{
		$string = $Request->query->get('s');
		
		$em = $this->getDoctrine()->getManager();

		$repo  = $em->getRepository('BusStopBundle:BusStop');
		$query = $repo->createQueryBuilder('a')
	               ->where('a.name LIKE :name')
	               ->setParameter('name', '%'.$string.'%')
	               ->groupBy('a.name')
	               ->getQuery();

	    $list = array();
	    foreach( $query->getResult() as $busStop ) {
	    	$list['results'][ ] = array( 'name' => $busStop->getName() );
	    }
		return new JsonResponse($list);
	}

    public function getLineTypesAction(Request $Request)
    {

        #if($Request->isXmlHttpRequest()) {
    	$type = $Request->request->get('type');

        $em = $this->getDoctrine()->getManager();
        

        if( $type == 'name' ) {
        	$repository = $em->getRepository('BusStopBundle:LineType');
	        foreach ($repository->findAll() as $line) {
	        	$lines[] = [ 'name' => $line->getName() ];
	        }
        } else {
        	$repository = $em->getRepository('BusStopBundle:Line');
	        foreach ($repository->findAll() as $line) {
	        	$lines[$line->getLinetype()->getName()][] = array('lineNumber' => $line->getId());
	        }
        }


        return new JsonResponse($lines);
    }

    public function getMessageAction(Request $Request)
    {
        
        $dayLimit = $Request->request->get('dayLimit');
        $today = new \DateTime();

        if( isset($dayLimit) ) {
        	$date = new \DateTime($dayLimit);
        } else {
        	$date = $today;
        }

        $msgRepo = $this->getDoctrine()
            ->getRepository('BusStopBundle:Message');

        $category = $msgRepo->createQueryBuilder('cc')
            ->select('DISTINCT cc.date, cc.message_short, cc.message_full')
            ->Where('cc.date >= :date')
            ->setParameter('date', $date)
            ->getQuery();

        
        $messages = array();
        foreach ($category->getResult() as $msg) {
            $msgDate = new \DateTime($msg['date']);

            /* upcoming event */
            if ($msgDate > $today) {
                $class = 'panel-warning';
            } else {
                $class = 'panel-default';
            }

            $messages[$msg['date']][] = array(
                'short' => $msg['message_short'],
                'long'  => $msg['message_full'],
                'class' => $class
            );
        }

        return new JsonResponse($messages);
    }
}
