<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Observation
 *
 * @ORM\Table(name="observation", indexes={@ORM\Index(name="fk_association2", columns={"utilisateur"}), @ORM\Index(name="fk_association3", columns={"courrier"})})
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\ObservationRepository")
 */
class Observation
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
     * @ORM\Column(name="libelle", type="string", length=254, nullable=true)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=true)
     */
    private $observation;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=254, nullable=true)
     */
    private $service;

    /**
     * @var boolean
     *
     * @ORM\Column(name="traite", type="boolean", nullable=true)
     */
    private $traite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajout", type="datetime", nullable=true)
     */
    private $dateajout;

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
     * @var Courrier
     *
     * @ORM\ManyToOne(targetEntity="Courrier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="courrier", referencedColumnName="id")
     * })
     */
    private $courrier;

    public function __construct()
    {
        $this->dateajout = new \DateTime();
        $this->traite = false;
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
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    /**
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * @param string $observation
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
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
     * @return bool
     */
    public function isTraite()
    {
        return $this->traite;
    }

    /**
     * @param bool $traite
     */
    public function setTraite($traite)
    {
        $this->traite = $traite;
    }

    /**
     * @return \DateTime
     */
    public function getDateajout()
    {
        return $this->dateajout;
    }

    /**
     * @param \DateTime $dateajout
     */
    public function setDateajout($dateajout)
    {
        $this->dateajout = $dateajout;
    }

    /**
     * @return \Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param \Utilisateur $utilisateur
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    /**
     * @return \Courrier
     */
    public function getCourrier()
    {
        return $this->courrier;
    }

    /**
     * @param \Courrier $courrier
     */
    public function setCourrier($courrier)
    {
        $this->courrier = $courrier;
    }


}

