<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Artiste
 *
 * @ORM\Table(name="artiste", indexes={@ORM\Index(name="fk_association3", columns={"con_id"})})
 * @ORM\Entity
 */
class Artiste
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=254, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=254, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=254, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="datenaissance", type="string", length=254, nullable=true)
     */
    private $datenaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="nationalite", type="string", length=254, nullable=true)
     */
    private $nationalite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdat;

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
        $this->createdat = new \DateTime();
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Artiste
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Artiste
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Artiste
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set datenaissance
     *
     * @param string $datenaissance
     *
     * @return Artiste
     */
    public function setDatenaissance($datenaissance)
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    /**
     * Get datenaissance
     *
     * @return string
     */
    public function getDatenaissance()
    {
        return $this->datenaissance;
    }

    /**
     * Set nationalite
     *
     * @param string $nationalite
     *
     * @return Artiste
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     *
     * @return Artiste
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set con
     *
     * @param \HomeBundle\Entity\Concert $con
     *
     * @return Artiste
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
