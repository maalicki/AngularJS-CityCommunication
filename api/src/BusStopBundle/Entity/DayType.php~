<?php
/**
 * Description of DayType
 *
 * @author Lukasz Malicki
 * @date Jun 9, 2015
 */
namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="daytype")
 * 
 */
class DayType
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=20, nullable=false)
     */
    private $type;
        
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Arrival", mappedBy="dayType")
     * 
     * @var line
     */
    private $arrival;

    
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
     * Set type
     *
     * @param string $type
     * @return DayType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return DayType
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
     * Add arrival
     *
     * @param \BusStopBundle\Entity\Arrival $arrival
     * @return DayType
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
}
