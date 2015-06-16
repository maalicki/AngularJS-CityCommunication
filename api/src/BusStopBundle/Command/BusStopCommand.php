<?php
/**
 * Description of Command
 *
 * @author Lukasz Malicki
 * @date May 28, 2015
 */
namespace BusStopBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Goutte\Client;
use BusStopBundle\Entity as BusStops;

class BusStopCommand extends ContainerAwareCommand
{

    private $dayTypes;
    private $lineTypes;
    private $cities;

    protected function configure()
    {
        $this
            ->setName('busstop:generate')
            ->setDescription('Generates bus lines')
            ->addArgument(
                'lines', InputArgument::OPTIONAL, 'Who do you want to greet?'
            )
            ->addOption(
                'yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lines = $input->getArgument('lines');
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        if (strtolower($lines) != 'all' && !preg_match('/^[0-9]+-[0-9]+$/', $lines)) {
            $msg = "Parameter '$lines' is incorrect.\nUseage:\n-all to update all lines with one query "
                . "(slow)\n1-100 for lines starting from 1 to 300\n20-34 for lines starting from 20 to 34, and so on...";
            throw new \Exception($msg);
        } elseif( preg_match('/^[0-9]+-[0-9]+$/', $lines) ) {
            list($start, $stop) = explode( '-', $lines);
        }

        $beginning = $this->timer();
        
        echo PHP_EOL, 'Getting cities', PHP_EOL;
        $this->getCities();
        echo PHP_EOL, 'Getting line types', PHP_EOL;
        $this->getLineType();
        echo PHP_EOL, 'Getting day types', PHP_EOL;
        $this->getDayType();


        $client = new Client();
        $lines = array();
        $kzk_href = 'http://rozklady.kzkgop.pl/kzkgo/';

        /**
         *  get lines 
         */
        echo 'Downloading linee types and numbers';
        $crawler = $client->request('GET', $kzk_href . 'index.php?co=rozklady')
            #->filter('div#wszystkie_linie > div.zbior_linii > a');
            ->filter('div#wszystkie_linie ');
        
            $ar = [
              'Tramwaje' => 1,
              'Autobusy' => 2,
              'Komunikacja nocna' => 3,
              'Bezpłatne' => 4,
              'Koleje Śląskie' => 5,
            ];
        
        foreach (range(0, $crawler->children()->count() - 1) as $num) {
            if (!empty(trim($crawler->children()->eq($num)->text()))) {
                if ($crawler->children()->eq($num)->nodeName() == 'b') {
                    $lineType = rtrim($crawler->children()->eq($num)->text(), ':');
                    #$this->getLineType( $lineType );
                }
                if ($crawler->children()->eq($num)->attr('class') == 'zbior_linii') {
                    foreach (range(0, $crawler->children()->eq($num)->children()->count() - 1) as $lineNum) {
                        
                        $line = new BusStops\Line;
                        $line->setLinetype( $em->getReference('BusStopBundle\Entity\LineType', $ar[$lineType]) )
                            #->setLinetype( $this->lineTypes[$lineType] )
                            ->setNumber($crawler->children()->eq($num)->children()->eq($lineNum)->children()->text())
                            ->setUrl($crawler->children()->eq($num)->children()->eq($lineNum)->attr('href'));

                        $lines[] = $line;
                    }
                }
            }
        }

        echo PHP_EOL, 'Downloading line stops';
        
        
        $start = ( $start >= 0 ? $start : 0);
        $stop = ($stop > 0 ? $stop : count($lines));
        $total = $stop-$start;
        
        $unavailableLines = array();
        #foreach ($lines as $number => $line) {
        foreach ( range( $start, $stop ) as $number ) {
            $line = $lines[$number];

            echo PHP_EOL, 'Line ' . $line->getNumber() . ' (' . $number . '/ ' . $stop . ')', PHP_EOL;

            $crawlers = $client->request('GET', $kzk_href . $line->getUrl());
            $crawler = $this->isCurrentLine($crawlers);

            if ($crawler->filter('div#content > h3')->count() > 0 && strpos($crawler->filter('div#content > h3')->text(), "spróbuj ponownie za kilka minut") > 0) {
                $unavailableLines[] = $lines[$number];
                unset($lines[$number]);
                echo PHP_EOL, '> Line ' . $line->getNumber() . ' is currently unavaiable.';
                continue;
            }

            /* get messages */
            echo 'Getting messages';
            foreach ($this->getMessages($crawler) as $row) {
                echo '.';
                $date = new \DateTime($row['date']);
                $msg = new BusStops\Message;
                $msg->setDate($date->format('Y-m-d'))
                    ->setMessageFull($row['long'])
                    ->setMessageShort($row['short'])
                    ->setLine($line);

                $em->persist($msg);
            }

            echo PHP_EOL, 'Getting timetable - arrivals', PHP_EOL;
            foreach ($this->getDirections($crawler) as $directionName => $direction) {
                foreach (range(1, ($direction->count()) - 1) as $num) {
                    
                    $busStopLink = $direction->eq($num)->filter('td a')->attr('href');
                    preg_match("/nr_przyst=\d+/", $busStopLink, $preg_line);
                    list( $string, $busStopNumber ) = explode('=', $preg_line[0]);

                    preg_match("/gmina_\d+/", $direction->eq($num)->filter('td:first-child')->attr('class'), $preg_city);
                    list( $string, $cityId ) = explode('_', $preg_city[0]);

                    $table = $this->getArrivals($kzk_href, $busStopLink);
                    
                    $busStop = new BusStops\BusStop;
                    $busStop->setDirection($directionName)
                        ->addLine($line)
                        ->setCity( $this->cities[$cityId])
                        #->setCity( $em->getReference('BusStopBundle\Entity\City', $cityId) )
                        ->setNumber($busStopNumber)
                        ->setName($direction->eq($num)->filter('td a')->text());

                    if ($table) {
                        $timeTable = new BusStops\Timetable();
                        foreach ($table['timetable'] as $arrival) {
                            $timeTable->addArrival($arrival)
                                ->setLine($line)
                                ->addBusstop($busStop);
                            $arrival->setTimetable($timeTable);
                            $em->persist($arrival);
                        }
                        $em->persist($timeTable);
                    }

                    $busStop->setTimetable($timeTable);
                    $em->persist($busStop);
                }
                
                $em->persist($line);
                
                if( $number > 0 && $number % 5 == 0 && ( in_array($directionName, array('r','c') ))) {
                    $em->flush();
                    #$em->clear();
                    
                    /**
                     * as we clear all objects, we need to get this objects once again
                     */
                    #this->getCities();
                    $this->getDayType();
                    #$this->getLineType();
                    
                    $seconds = 15;
                    echo PHP_EOL , "Wait $seconds:  ";
                    foreach( range( $seconds, 1) as $sec ) {
                        echo $sec , ' ';
                        sleep(1);
                    }
                }
                
                echo PHP_EOL , '  # Script runtime:  '. gmdate("H:i:s" , round($this->timer()-$beginning) );
                echo PHP_EOL , '  # ETA:  '. gmdate("H:i:s" ,  ($total*round($this->timer()-$beginning))/($number+1) ) ;
                echo PHP_EOL , '  # ETA:  '. gmdate("H:i:s" , ($total-($number+1)) *  round($this->timer()-$beginning)/($number+1) );
                    
            }
        }
        $em->flush();
    }

    protected function isCurrentLine($crawler)
    {
        if ($crawler->filter('div#div_tabelki_tras > ul')->count() > 0) {
            if ($crawler->filter('div#div_tabelki_tras > h3')->text() == 'Wybierz interesujący Cię rozkład') {

                $ul = $crawler->filter('div#div_tabelki_tras > ul li');
                $url = $ul->each(function ($node, $i) {
                    if (strpos($node->text(), 'obecnie obowiązujący')) {
                        return $node->children()->attr('href');
                    }
                });

                if (empty($url)) {
                    throw new \Exception('Can\'t find actual URL for line "' . trim($crawler->filter('div#div_p2')->text()) . '"');
                }

                $client = new Client();

                $kzk_href = 'http://rozklady.kzkgop.pl/kzkgo/';

                echo 'Downloading new content';
                return $client->request('GET', $kzk_href . $url[0]);
            }
        }
        return $crawler;
    }

    protected function getMessages($crawler)
    {
        $notifications = $crawler->filter('div#komunikaty > div#komunikaty_in > div.InfoCalosc');
        $msg = $notifications->each(function ($node, $i) {
            return [
                'date' => trim($node->filter('div.info_data')->text()),
                'short' => substr($node->filter('div.zmianyMainInfo')->text(), 0, -18),
                'long' => trim($node->filter('div.ZmianyInfo')->text())
            ];
        });
        return $msg;
    }

    protected function getDirections($crawler)
    {
        if ($crawler->filter('div#lewo > table.trasy_tabelka tr')->count() > 0) {
            $directions['l'] = $crawler->filter('div#lewo > table.trasy_tabelka tr');
            $directions['r'] = $crawler->filter('div#prawo > table.trasy_tabelka tr');
        } elseif ($crawler->filter('div#srodek > table.trasy_tabelka tr')->count() > 0) {
            $directions['c'] = $crawler->filter('div#srodek > table.trasy_tabelka tr');
        }
        return $directions;
    }

    public function getDayType($crawler = false)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $dm = $this->getContainer()->get('doctrine');
        
        $dayTypeRepo = $dm->getRepository('BusStopBundle:DayType');
        $dbDayType = $dayTypeRepo->findAll();
        
        if($dbDayType) {
            foreach( $dbDayType as $day ) {
                $this->dayTypes[ $day->getType() ] = $day;
            }
        }
        
        if( $crawler && !isset($this->dayTypes[ $crawler->attr('class') ]) ) {
            $dayTypeClass = $crawler->attr('class');
            $dayTypeName = $crawler->text();

            $dayType = new BusStops\DayType();
            $dayType->setType($dayTypeClass)
                ->setName($dayTypeName);

            $this->dayTypes[$dayTypeClass] = $dayType;
            $em->persist($dayType);
            #$em->flush();  
        }
    }

    public function getLineType()
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $dm = $this->getContainer()->get('doctrine');
        
        $lineTypeRepo = $dm->getRepository('BusStopBundle:LineType');
        $dbLineType = $lineTypeRepo->findAll();
        
        if($dbLineType) {
            foreach( $dbLineType as $lineType ) {
                $this->lineTypes[ $lineType->getName() ] = $lineType;
            }
        }
        
        $client = new Client();
        $kzk_href = 'http://rozklady.kzkgop.pl/kzkgo/';

        $crawler = $client->request('GET', 'http://rozklady.kzkgop.pl/index.php?co=rozklady')
            ->filter('div#wszystkie_linie b');
        
        foreach (range(0, $crawler->count() - 1) as $num) {
            
            preg_match("/[A-Za-ż\s]+/", $crawler->eq($num)->text(), $line);
            
            if( !isset($this->lineTypes[ $line[0] ]) ) {
                $lineType = new BusStops\LineType();
                $lineType->setName($line[0]);

                $this->lineTypes[$line[0]] = $lineType;
                $em->persist($lineType);
                #$em->flush();
            }
        }
        
    }

    protected function getArrivals($base, $url)
    {
        /* $url = "index.php?co=rozklady&submenu=tabliczka&nr_linii=T0&nr_przyst=1&id_trasy=14136"; */

        $parts = parse_url($url);
        $output = [];
        parse_str($parts['query'], $data);

        $print = 'wydruk.php?plik=przystankowo' . $data['nr_przyst'] . '_' . $data['id_trasy'] . '.php&numer_linii=' . $data['nr_linii'];

        $client = new Client();
        $crawler = $client->request('GET', $base . $print)->filter('div#rozklad_tabliczka');

        $arrivals = $crawler->filter('div#div_rozklad_tabliczka > table#tabliczka_przystankowo > tr');

        /**
         * if there is no arrivals, return empry array
         */
        if (!$arrivals->count()) {
            return array();
        }

        /**
         * get buss stop and direction
         */
        $arrives = array();
        $arrives['from'] = $crawler->filter('div#tabliczka_topinfo h2')->text();
        $arrives['to'] = $crawler->filter('div#tabliczka_topinfo h3')->text();

        $date_str = $crawler->filter('div#div_rozklad_tabliczka > div.legenda > span#lenegnda_data')->text();
        preg_match("/[0-9]{1,4}-[0-9]{1,2}-[0-9]{1,2}/", $date_str, $preg_date);

        /**
         * get last update
         */
        $date = new \DateTime($preg_date[0]);
        $arrives['validFrom'] = $date->format('Y-m-d');

        foreach (range(0, $arrivals->count() - 1) as $elem) {

            if ($arrivals->eq($elem)->children()->nodeName() == 'th') {
                /**
                 * Update dayTypes. If it is new dayType it will create new Object
                 */
                $dayTypeClass = $arrivals->eq($elem)->attr('class');
                $this->getDayType($arrivals->eq($elem));
            } else {
                #echo '  getting arrivals';
                $span = $arrivals->eq($elem)->filter('span#blok_godzina');
                foreach (range(1, $span->count()) as $num) {
                    $hour = $span->filter('b')->text() < 10 ? '0' . $span->filter('b')->text() : $span->filter('b')->text();

                    $minutes = $span->children()->filter('a');
                    foreach (range(0, $minutes->count() - 1) as $min) {
                        #echo '.';
                        #$leged   = substr( $minutes->eq($min)->text(), 2 , 1);
                        $minute = substr($minutes->eq($min)->text(), 0, 2);

                        $time = new \DateTime($hour . ':' . $minute);
                        
                        $arrival = new BusStops\Arrival();
                        $arrival->setDayType($this->dayTypes[$dayTypeClass])
                            ->setTime($time->format('H:i:00'));

                        $arrives['timetable'][] = $arrival;
                    }
                }
                #echo PHP_EOL, PHP_EOL;
            }
        }

        #wymaga przeiterowania!
        #$arrives['legend'] = $crawler->filter('div#div_rozklad_tabliczka > div.legenda > table.legenda_literki tr')->text();

        /**
         * http://rozklady.kzkgop.pl/kzkgo/index.php?co=rozklady&submenu=tabliczka&nr_linii=T3&nr_przyst=18&id_trasy=13725
         * http://rozklady.kzkgop.pl/kzkgo/index.php?co=rozklady&submenu=tabliczka&nr_linii=T0&nr_przyst=1&id_trasy=14136
         */
        return $arrives;
    }

    private function getCities()
    {
        
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $dm = $this->getContainer()->get('doctrine');
        
        $cityRepo = $dm->getRepository('BusStopBundle:City');
        $dbCities = $cityRepo->findAll();
        
        if($dbCities) {
            foreach( $dbCities as $city ) {
                $this->cities[ $city->getCityid() ] = $city;
            }
            return; 
        }
        
        
        $client = new Client();
        $crawler = $client->request('GET', 'http://rozklady.kzkgop.pl/index.php?co=rozklady&submenu=przystanki');

        foreach (range(0, $crawler->filter('table.tabelka_gmin tr td:first-child a')->count() - 1) as $num) {

            preg_match('/(\d+)\D*$/', $crawler->filter('table.tabelka_gmin tr td:first-child a')->eq($num)->attr('href'), $cityId);

            $city = new BusStops\City();
            $city->setName($crawler->filter('table.tabelka_gmin tr td:first-child a')->eq($num)->text())
                ->setCityid($cityId[1]);

            $this->cities[$cityId[1]] = $city;
            $em->persist($city);
            $em->flush();
        }

    }

    private function timer()
    {
        $time = explode(' ', microtime());
        return $time[0] + $time[1];
    }
}
