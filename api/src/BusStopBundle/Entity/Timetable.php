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

    /**
     * @ORM\OneToMany(targetEntity="Arrival", mappedBy="timetable")
     * */
    private $arrival;
    
    /**
     * @ORM\OneToMany(targetEntity="BusStop", mappedBy="timetable")
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
        $this->busstop = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     * @return Timetable
     */
    public function addBusstop(\BusStopBundle\Entity\BusStop $busstop)
    {
        $this->busstop[] = $busstop;

        return $this;
    }

    /**
     * Remove busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     */
    public function removeBusstop(\BusStopBundle\Entity\BusStop $busstop)
    {
        $this->busstop->removeElement($busstop);
    }

    /**
     * Get busstop
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusstop()
    {
        return $this->busstop;
    }
}
