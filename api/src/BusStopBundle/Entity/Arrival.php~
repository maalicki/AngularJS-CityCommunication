<?php

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="arrival")
 * 
 */
class Arrival
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="time", type="string", nullable=false)
     */
    private $time;
    
//    /**
//     * @ORM\OneToMany(targetEntity="Timetable", mappedBy="arrival")
//     * */
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="arrival")
     * @var Line
     */
    private $timetable;
    
    /**
     * @ORM\ManyToOne(targetEntity="DayType", inversedBy="arrival", cascade={"persist"})
     * @ORM\JoinColumn(name="dayType", referencedColumnName="id")
     *
     */
    private $dayType;

    
    


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set time
     *
     * @param string $time
     * @return Arrival
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set timetable
     *
     * @param \BusStopBundle\Entity\Timetable $timetable
     * @return Arrival
     */
    public function setTimetable(\BusStopBundle\Entity\Timetable $timetable = null)
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * Get timetable
     *
     * @return \BusStopBundle\Entity\Timetable 
     */
    public function getTimetable()
    {
        return $this->timetable;
    }

    /**
     * Set dayType
     *
     * @param \BusStopBundle\Entity\DayType $dayType
     * @return Arrival
     */
    public function setDayType(\BusStopBundle\Entity\DayType $dayType = null)
    {
        $this->dayType = $dayType;

        return $this;
    }

    /**
     * Get dayType
     *
     * @return \BusStopBundle\Entity\DayType 
     */
    public function getDayType()
    {
        return $this->dayType;
    }
}
