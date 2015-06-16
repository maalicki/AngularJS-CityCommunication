<?php 

namespace BusStopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 * 
 */
class Message
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Line", inversedBy="messages")
     * @ORM\JoinColumn(name="line", referencedColumnName="id")
     * @var timetable
     */
    private $line;

    /**
     * @ORM\Column(name="date", type="string", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(name="message_full", type="text")
     */
    private $message_full;

    /**
     * @ORM\Column(name="message_short", type="string")
     */
    private $message_short;


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
     * Set date
     *
     * @param \DateTime $date
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set line
     *
     * @param \BusStopBundle\Entity\Line $line
     * @return Message
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
     * Set message_full
     *
     * @param string $messageFull
     * @return Message
     */
    public function setMessageFull($messageFull)
    {
        $this->message_full = $messageFull;

        return $this;
    }

    /**
     * Get message_full
     *
     * @return string 
     */
    public function getMessageFull()
    {
        return $this->message_full;
    }

    /**
     * Set message_short
     *
     * @param string $messageShort
     * @return Message
     */
    public function setMessageShort($messageShort)
    {
        $this->message_short = $messageShort;

        return $this;
    }

    /**
     * Get message_short
     *
     * @return string 
     */
    public function getMessageShort()
    {
        return $this->message_short;
    }
}
