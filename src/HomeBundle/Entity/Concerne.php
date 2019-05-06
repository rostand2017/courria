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
     * @ORM\ManyToOne(targetEntity="Concert", cascade="persist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concert_id", referencedColumnName="id")
     * })
     */
    private $concert;

    /**
     * @var \Artiste
     *
     * @ORM\OneToMany(targetEntity="Artiste", mappedBy="concerne")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artiste_id", referencedColumnName="id")
     * })
     */
    private $artiste;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->artiste = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add artiste
     *
     * @param \HomeBundle\Entity\Artiste $artiste
     *
     * @return Concerne
     */
    public function addArtiste(\HomeBundle\Entity\Artiste $artiste)
    {
        $this->artiste[] = $artiste;

        return $this;
    }

    /**
     * Remove artiste
     *
     * @param \HomeBundle\Entity\Artiste $artiste
     */
    public function removeArtiste(\HomeBundle\Entity\Artiste $artiste)
    {
        $this->artiste->removeElement($artiste);
    }

    /**
     * Get artiste
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArtiste()
    {
        return $this->artiste;
    }
}
