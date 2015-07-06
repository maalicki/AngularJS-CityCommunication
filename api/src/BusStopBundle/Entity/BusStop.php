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
     * @ORM\ManyToOne(targetEntity="BusStopName", inversedBy="busstop", cascade={"persist"})
     * @ORM\JoinColumn(name="busstop", referencedColumnName="id")
     *
     */
    private $busstopid;
    
    /**
     * @ORM\Column(name="busstop_from", type="string", nullable=true)
     */
    private $from;
    
    /**
     * @ORM\Column(name="busstop_to", type="string", nullable=true)
     */
    private $to;
    
    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="busstop", cascade={"persist"})
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
     *
     */
    private $city;
    
//    /**
//     * @ORM\ManyToOne(targetEntity="Timetable", inversedBy="busstop")
//     * @ORM\JoinColumn(name="timetable", referencedColumnName="id")
//     *
//     */
    /**
     * @ORM\OneToMany(targetEntity="Timetable", mappedBy="busstop")
     * */
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
        $this->timetable = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set from
     *
     * @param string $from
     * @return BusStop
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return BusStop
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string 
     */
    public function getTo()
    {
        return $this->to;
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
     * Set busstopid
     *
     * @param \BusStopBundle\Entity\BusStopName $busstopid
     * @return BusStop
     */
    public function setBusstopid(\BusStopBundle\Entity\BusStopName $busstopid = null)
    {
        $this->busstopid = $busstopid;

        return $this;
    }

    /**
     * Get busstopid
     *
     * @return \BusStopBundle\Entity\BusStopName 
     */
    public function getBusstopid()
    {
        return $this->busstopid;
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
     * Add timetable
     *
     * @param \BusStopBundle\Entity\Timetable $timetable
     * @return BusStop
     */
    public function addTimetable(\BusStopBundle\Entity\Timetable $timetable)
    {
        $this->timetable[] = $timetable;

        return $this;
    }

    /**
     * Remove timetable
     *
     * @param \BusStopBundle\Entity\Timetable $timetable
     */
    public function removeTimetable(\BusStopBundle\Entity\Timetable $timetable)
    {
        $this->timetable->removeElement($timetable);
    }

    /**
     * Get timetable
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTimetable()
    {
        return $this->timetable;
    }
}
