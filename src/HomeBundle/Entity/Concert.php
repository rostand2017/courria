<?php

namespace HomeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Concert
 *
 * @ORM\Table(name="concert", indexes={@ORM\Index(name="fk_association2", columns={"sal_id"})})
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\ConcertRepository")
 */
class Concert
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
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    private $prix;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbplace", type="integer", nullable=true)
     */
    private $nbplace;

    /**
     * @var string
     *
     * @ORM\Column(name="intitule", type="string", length=254, nullable=true)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=254, nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     *
     * @ORM\Column(name="heure", type="time", nullable=false)
     */
    private $heure;

    /**
     * @var string
     *
     * @ORM\Column(name="affiche", type="string", length=254, nullable=true)
     */
    private $affiche;

    /**
     * @var \Salle
     *
     * @ORM\ManyToOne(targetEntity="Salle")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sal_id", referencedColumnName="id")
     * })
     */
    private $sal;

    /**
     *
     * @ORM\OneToMany(targetEntity="Concerne", mappedBy="concert", cascade={"persist", "remove"})
     */
    private $concerne;


    public function __construct()
    {
        $this->concerne = new ArrayCollection();
    }

    /**
     * Concert constructor.
     * @param int $prix
     * @param int $nbplace
     * @param string $intitule
     * @param string $description
     * @param \DateTime $date
     * @param string $affiche
     * @param \Salle $sal
     */

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
     * Set prix
     *
     * @param integer $prix
     *
     * @return Concert
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set nbplace
     *
     * @param integer $nbplace
     *
     * @return Concert
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
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Concert
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Concert
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Concert
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
     * Set affiche
     *
     * @param string $affiche
     *
     * @return Concert
     */
    public function setAffiche($affiche)
    {
        $this->affiche = $affiche;

        return $this;
    }

    /**
     * Get affiche
     *
     * @return string
     */
    public function getAffiche()
    {
        return $this->affiche;
    }

    /**
     * Set sal
     *
     * @param \HomeBundle\Entity\Salle $sal
     *
     * @return Concert
     */
    public function setSal(\HomeBundle\Entity\Salle $sal = null)
    {
        $this->sal = $sal;

        return $this;
    }

    /**
     * Get sal
     *
     * @return \HomeBundle\Entity\Salle
     */
    public function getSal()
    {
        return $this->sal;
    }

    /**
     * Set heure
     *
     * @param \DateTime $heure
     *
     * @return Concert
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return \DateTime
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * Add concerne
     *
     * @param \HomeBundle\Entity\Concerne $concerne
     *
     * @return Concert
     */
    public function addConcerne(\HomeBundle\Entity\Concerne $concerne)
    {
        $this->concerne[] = $concerne;

        return $this;
    }

    /**
     * Remove concerne
     *
     * @param \HomeBundle\Entity\Concerne $concerne
     */
    public function removeConcerne(\HomeBundle\Entity\Concerne $concerne)
    {
        $this->concerne->removeElement($concerne);
    }

    /**
     * Get concerne
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcerne()
    {
        return $this->concerne;
    }
}
