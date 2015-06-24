<?php 

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="busstop_name")
 * 
 */
class BusStopName
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="BusStop", mappedBy="busstopid")
     * 
     * @var line
     */
    private $busstop;
    /**
     * Constructor
     */
    public function __construct()
    {
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
     * Set name
     *
     * @param string $name
     * @return BusStopName
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
     * Add busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     * @return BusStopName
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
