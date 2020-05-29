<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\FactureRepository")
 */
class Facture
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
     * @ORM\Column(name="nomclient", type="string", length=254, nullable=false)
     */
    private $nomclient;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=false)
     */
    private $createdat;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Factureproduit", mappedBy="facture", cascade={"remove"})
     */
    private $fatureProduit;

    public function __construct()
    {
        $this->createdat = new \DateTime();
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
    public function getNomclient()
    {
        return $this->nomclient;
    }

    /**
     * @param string $nomclient
     */
    public function setNomclient($nomclient)
    {
        $this->nomclient = $nomclient;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * @param \DateTime $createdat
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;
    }

    /**
     * @return array
     */
    public function getFatureProduit()
    {
        return $this->fatureProduit;
    }

    /**
     * @param array $fatureProduit
     */
    public function setFatureProduit($fatureProduit)
    {
        $this->fatureProduit = $fatureProduit;
    }

}

