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

        foreach ($repository->findAll() as $line) {
            $lines[$line->getLinetype()->getName()][] = array('lineNumber' => $line->getId());
        }

        return new JsonResponse($lines);
    }

    public function getMessageAction(Request $Request)
    {
        
        $dayLimit = ( $Request->request->get('dayLimit') ? $Request->request->get('dayLimit') : 7);
        $queryLimit = ( $Request->request->get('queryLimit') ? $Request->request->get('queryLimit') : 5);

        $date = new \DateTime();
        $date->modify("-$dayLimit day");

        $msgRepo = $this->getDoctrine()
            ->getRepository('BusStopBundle:Message');

        $category = $msgRepo->createQueryBuilder('cc')
            ->select('DISTINCT cc.date, cc.message_short, cc.message_full')
            ->Where('cc.date >= :date')
            ->setParameter('date', $date)
            ->getQuery();

        $today = new \DateTime();
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
