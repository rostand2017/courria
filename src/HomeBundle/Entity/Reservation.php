<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_assiste", columns={"cli_id"}), @ORM\Index(name="fk_association1", columns={"con_id"})})
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="nbplace", type="integer", nullable=true)
     */
    private $nbplace;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cli_id", referencedColumnName="id")
     * })
     */
    private $cli;

    /**
     * @var \Concert
     *
     * @ORM\ManyToOne(targetEntity="Concert")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="con_id", referencedColumnName="id")
     * })
     */
    private $con;

    public function __construct()
    {
        $this->date = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservation
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
     * Set nbplace
     *
     * @param integer $nbplace
     *
     * @return Reservation
     */
    public function setNbplace($nbplace)
    {
        $this->nbplace = $nbplace;

        return $this;
    }

    /**
     * Get nbplace
     *
     * @return integer
     */
    public function getNbplace()
    {
        return $this->nbplace;
    }

    /**
     * Set cli
     *
     * @param \HomeBundle\Entity\Client $cli
     *
     * @return Reservation
     */
    public function setCli(\HomeBundle\Entity\Client $cli = null)
    {
        $this->cli = $cli;

        return $this;
    }

    /**
     * Get cli
     *
     * @return \HomeBundle\Entity\Client
     */
    public function getCli()
    {
        return $this->cli;
    }

    /**
     * Set con
     *
     * @param \HomeBundle\Entity\Concert $con
     *
     * @return Reservation
     */
    public function setCon(\HomeBundle\Entity\Concert $con = null)
    {
        $this->con = $con;

        return $this;
    }

    /**
     * Get con
     *
     * @return \HomeBundle\Entity\Concert
     */
    public function getCon()
    {
        return $this->con;
    }
}
