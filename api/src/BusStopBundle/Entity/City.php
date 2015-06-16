<?php 

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="city")
 * 
 */
class City
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="BusStop", mappedBy="city")
     * 
     * @var line
     */
    private $busstop;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;
    
    /**
     * @ORM\Column(name="cityid", type="integer", nullable=false, options={"unsigned"=true}, unique=true)
     */
    private $cityid;


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
     * @return City
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
     * Set cityid
     *
     * @param integer $cityid
     * @return City
     */
    public function setCityid($cityid)
    {
        $this->cityid = $cityid;

        return $this;
    }

    /**
     * Get cityid
     *
     * @return integer 
     */
    public function getCityid()
    {
        return $this->cityid;
    }

    /**
     * Add busstop
     *
     * @param \BusStopBundle\Entity\BusStop $busstop
     * @return City
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
