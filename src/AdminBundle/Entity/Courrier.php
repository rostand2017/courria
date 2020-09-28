<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Courrier
 *
 * @ORM\Table(name="courrier", indexes={@ORM\Index(name="fk_association1", columns={"utilisateur"})})
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\CourrierRepository")
 */
class Courrier
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
     * @ORM\Column(name="expediteur", type="string", length=254, nullable=true)
     */
    private $expediteur;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=254, nullable=true)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=254, nullable=true)
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=254, nullable=true)
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateexpedition", type="datetime", nullable=true)
     */
    private $dateexpedition;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="utilisateur", referencedColumnName="id")
     * })
     */
    private $utilisateur;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Observation",mappedBy="courrier", cascade={"persist", "remove"})
     */
    private $observation;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AdminBundle\Entity\Files", mappedBy="courrier", cascade={"persist", "remove"})
     */
    private $files;


    public function __construct()
    {
        $this->dateexpedition = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getExpediteur()
    {
        return $this->expediteur;
    }

    /**
     * @param string $expediteur
     */
    public function setExpediteur($expediteur)
    {
        $this->expediteur = $expediteur;
    }

    /**
     * @return ArrayCollection
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param ArrayCollection $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
    }


    /**
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * @param string $objet
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return \DateTime
     */
    public function getDateexpedition()
    {
        return $this->dateexpedition;
    }

    /**
     * @param \DateTime $dateexpedition
     */
    public function setDateexpedition($dateexpedition)
    {
        $this->dateexpedition = $dateexpedition;
    }

    /**
     * @return Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }


}

