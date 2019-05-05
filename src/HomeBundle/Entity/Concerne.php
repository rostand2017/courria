<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concerne
 *
 * @ORM\Table(name="concerne", indexes={@ORM\Index(name="fk_concerne", columns={"concert_id"}), @ORM\Index(name="fk_effectue", columns={"artiste_id"})})
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\ConcerneRepository")
 */
class Concerne
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
     * @var \Concert
     *
     * @ORM\ManyToOne(targetEntity="Concert")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concert_id", referencedColumnName="id")
     * })
     */
    private $concert;

    /**
     * @var \Artiste
     *
     * @ORM\ManyToOne(targetEntity="Artiste")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artiste_id", referencedColumnName="id")
     * })
     */
    private $artiste;



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
     * Set concert
     *
     * @param \HomeBundle\Entity\Concert $concert
     *
     * @return Concerne
     */
    public function setConcert(\HomeBundle\Entity\Concert $concert = null)
    {
        $this->concert = $concert;

        return $this;
    }

    /**
     * Get concert
     *
     * @return \HomeBundle\Entity\Concert
     */
    public function getConcert()
    {
        return $this->concert;
    }

    /**
     * Set artiste
     *
     * @param \HomeBundle\Entity\Artiste $artiste
     *
     * @return Concerne
     */
    public function setArtiste(\HomeBundle\Entity\Artiste $artiste = null)
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * Get artiste
     *
     * @return \HomeBundle\Entity\Artiste
     */
    public function getArtiste()
    {
        return $this->artiste;
    }
}
