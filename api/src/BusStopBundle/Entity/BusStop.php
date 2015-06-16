<?php 

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="busstop")
 * 
 */
class BusStop
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToMany(targetEntity="Line", inversedBy="busstop")
     * @ORM\JoinTable(name="busstop_line",
     *     joinColumns={@ORM\JoinColumn(name="busstop", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="line", referencedColumnName="id")}
     * )
     * @var line
     */
    private $line;

    /**
     * @ORM\Column(name="direction", type="string", length=1, nullable=false)
     */
    private $direction;
    
    
    /**
     * @ORM\Column(name="stopnumber", type="string", length=5, nullable=false)
     */
    private $number;
    
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="busstop", cascade={"persist"})
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
     *
     */
    private $city;
    
    /**
     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="busstop")
     * @ORM\JoinColumn(name="timetable", referencedColumnName="id")
     *
     */
    private $timetable;

    public function __toString()
    {
        return 'BusStop';
    }
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->line = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set direction
     *
     * @param string $direction
     * @return BusStop
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string 
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return BusStop
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BusStop
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add line
     *
     * @param \BusStopBundle\Entity\Line $line
     * @return BusStop
     */
    public function addLine(\BusStopBundle\Entity\Line $line)
    {
        $this->line[] = $line;

        return $this;
    }

    /**
     * Remove line
     *
     * @param \BusStopBundle\Entity\Line $line
     */
    public function removeLine(\BusStopBundle\Entity\Line $line)
    {
        $this->line->removeElement($line);
    }

    /**
     * Get line
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set city
     *
     * @param \BusStopBundle\Entity\City $city
     * @return BusStop
     */
    public function setCity(\BusStopBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \BusStopBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set timetable
     *
     * @param \BusStopBundle\Entity\Timetable $timetable
     * @return BusStop
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
}
