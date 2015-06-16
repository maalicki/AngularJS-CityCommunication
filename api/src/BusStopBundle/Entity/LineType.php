<?php 

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="linetype")
 * 
 */
class LineType
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\OneToMany(targetEntity="Line", mappedBy="linetype")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Line", mappedBy="linetype")
     * 
     */
    private $line;

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
     * @return LineType
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
     * Constructor
     */
    public function __construct()
    {
        $this->line = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add line
     *
     * @param \BusStopBundle\Entity\Line $line
     * @return LineType
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
}
