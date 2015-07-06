<?php

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timetable")
 * 
 */
class Timetable
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Line", inversedBy="linetype")
     * @ORM\JoinColumn(name="line", referencedColumnName="id")
     * @var Line
     */
    private $line;


//    /**
//     * @ORM\ManyToOne(targetEntity="Arrival", inversedBy="timetable")
//     * @ORM\JoinColumn(name="arrivalid", referencedColumnName="id")
//     * 
//     * @var line
//     */
    /**
     * @ORM\OneToMany(targetEntity="Arrival", mappedBy="timetable")
     * */
    private $arrival;
    
    /**
     * @ORM\ManyToOne(targetEntity="BusStop", inversedBy="timetable")
     * @ORM\JoinColumn(name="busstopid", referencedColumnName="id")
     * 
     * @var line
     */
    private $busstop;
    


    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->arrival = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set line
     *
     * @param \BusStopBundle\Entity\Line $line
     * @return Timetable
     */
    public function setLine(\BusStopBundle\Entity\Line $line = null)
    {
        $this->line = $line;

        return $this;
    }

    /**
     * Get line
     *
     * @return \BusStopBundle\Entity\Line 
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Add arrival
     *
     * @param \BusStopBundle\Entity\Arrival $arrival
     * @return Timetable
     */
    public function addArrival(\BusStopBundle\Entity\Arrival $arrival)
    {
        $this->arrival[] = $arrival;

        return $this;
    }

    /**
     * Remove arrival
     *
     * @param \BusStopBundle\Entity\Arrival $arrival
     */
    public function removeArrival(\BusStopBundle\Entity\Arrival $arrival)
    {
        $this->arrival->removeElement($arrival);
    }

    /**
     * Get arrival
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * Set busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     * @return Timetable
     */
    public function setBusstop(\BusStopBundle\Entity\BusStop $busstop = null)
    {
        $this->busstop = $busstop;

        return $this;
    }

    /**
     * Get busstop
     *
     * @return \BusStopBundle\Entity\BusStop 
     */
    public function getBusstop()
    {
        return $this->busstop;
    }
}
