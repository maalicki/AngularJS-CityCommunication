<?php namespace BusStopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    public function getTimeTableAction(Request $Request)
    {
        $string = $Request->request->get('busstop');

        $em = $this->getDoctrine()->getManager();

        $busStopNameRepo = $em->getRepository('BusStopBundle:BusStopName');
        $lineRepo = $em->getRepository('BusStopBundle:Line');

        $query = $busStopNameRepo->createQueryBuilder('a')
            ->where("a.name = :name")
            ->setParameter('name', $string)
            ->getQuery();

        $query = $query->getResult();
        
        $list = array();
        foreach ( $query[0]->getBusStop() as $busStop) {
            $arrival = array();
            
            foreach ($busStop->getLine() as $num) {
                $query = $lineRepo->findOneById($num->getId());

                if ($messages = $query->getMessages()->first()) {
                    $msg = $messages->getMessageShort();
                }

                $timeTable = array();
                $now = new \DateTime;
                foreach( $busStop->getTimetable() as $times ) {
                    $cc_times = $times->getArrival();
                
                    foreach ($cc_times as $time) {
                        if( $time->getTime() > $now->format('H:i:s') ) {
                            $timeTable[ $time->getDayType()->getName() ][] = $time->getTime();
                        }
                    }
                }

                $arrival = array(
                    'name' => $busStop->getBusstopid()->getName(),
                    'number' => $query->getNumber(),
                    'timetable' => $timeTable,
                    'msgShort' => isset($msg) ? $msg : null,
                    'timetable' => $timeTable,
                );
                
                $arrivalDetails = array(
                    'from' => $busStop->getFrom(),
                    'to' => $busStop->getTo(),
                );
                
                $lineType = $query->getLinetype()->getName();
                $lineNumber = $query->getNumber();
            }

            #$query = $lineRepo->findOneById( $busStop->getLine() );

            $list[$lineType][$busStop->getDirection()][$lineNumber] = $arrival;
            $list[$lineType][$busStop->getDirection()]['data'] = $arrivalDetails;
        }
        return new JsonResponse($list);
    }

    public function getBusStopNameAction(Request $Request)
    {
        $string = $Request->query->get('str');

        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('BusStopBundle:BusStopName');
        $query = $repo->createQueryBuilder('a')
            ->where('a.name LIKE :name')
            ->setParameter('name', '%' . $string . '%')
            ->getQuery();

        $list = array();
        foreach ($query->getResult() as $busStop) {
            $list['results'][] = array('name' => $busStop->getName());
        }
        return new JsonResponse($list);
    }

    public function getLineTypesAction(Request $Request)
    {

        #if($Request->isXmlHttpRequest()) {
        $type = $Request->request->get('type');

        $em = $this->getDoctrine()->getManager();


        if ($type == 'name') {
            $repository = $em->getRepository('BusStopBundle:LineType');
            foreach ($repository->findAll() as $line) {
                $lines[] = [ 'name' => $line->getName()];
            }
        } else {
            $repository = $em->getRepository('BusStopBundle:Line');
            foreach ($repository->findAll() as $line) {
                $lines[$line->getLinetype()->getName()][] = array('lineNumber' => $line->getNumber());
            }
        }


        return new JsonResponse($lines);
    }

    public function getMessageAction(Request $Request)
    {

        $dayLimit = $Request->request->get('dayLimit');
        $today = new \DateTime();

        if (isset($dayLimit)) {
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
                'long' => $msg['message_full'],
                'class' => $class
            );
        }

        return new JsonResponse($messages);
    }
}
