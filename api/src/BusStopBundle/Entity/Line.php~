<?php

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="line")
 * 
 */
class Line
{
    
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="number", type="string", length=5, nullable=false)
     */
    private $number;
    
    /**
     *@ORM\Column(name="url", type="string", length=200 )
     * @var string
     */
    private $url;
    

    /**
     * @ORM\ManyToOne(targetEntity="LineType", inversedBy="line", cascade={"persist"})
     * @ORM\JoinColumn(name="linetype", referencedColumnName="id")
     *
     */
    private $linetype;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Timetable", mappedBy="line")
     * */
    private $timetables;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="line")
     * */
    private $messages;
    
    /**
     * @ORM\OneToMany(targetEntity="BusStop", mappedBy="line")
     * */
    private $busstop;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->timetables = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->busstop = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        var_dump( $this );
        die();
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
     * Set number
     *
     * @param string $number
     * @return Line
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
     * Set url
     *
     * @param string $url
     * @return Line
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set linetype
     *
     * @param \BusStopBundle\Entity\LineType $linetype
     * @return Line
     */
    public function setLinetype(\BusStopBundle\Entity\LineType $linetype = null)
    {
        $this->linetype = $linetype;

        return $this;
    }

    /**
     * Get linetype
     *
     * @return \BusStopBundle\Entity\LineType 
     */
    public function getLinetype()
    {
        return $this->linetype;
    }

    /**
     * Add timetables
     *
     * @param \BusStopBundle\Entity\Timetable $timetables
     * @return Line
     */
    public function addTimetable(\BusStopBundle\Entity\Timetable $timetables)
    {
        $this->timetables[] = $timetables;

        return $this;
    }

    /**
     * Remove timetables
     *
     * @param \BusStopBundle\Entity\Timetable $timetables
     */
    public function removeTimetable(\BusStopBundle\Entity\Timetable $timetables)
    {
        $this->timetables->removeElement($timetables);
    }

    /**
     * Get timetables
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTimetables()
    {
        return $this->timetables;
    }

    /**
     * Add messages
     *
     * @param \BusStopBundle\Entity\Message $messages
     * @return Line
     */
    public function addMessage(\BusStopBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \BusStopBundle\Entity\Message $messages
     */
    public function removeMessage(\BusStopBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     * @return Line
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
