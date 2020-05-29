<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Factureproduit
 *
 * @ORM\Table(name="factureproduit", indexes={@ORM\Index(name="FK_ASSOCIATION3", columns={"facture"}), @ORM\Index(name="FK_ASSOCIATION4", columns={"produit"})})
 * @ORM\Entity
 */
class Factureproduit
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
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var Facture
     *
     * @ORM\ManyToOne(targetEntity="Facture", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture", referencedColumnName="id")
     * })
     */
    private $facture;

    /**
     * @var Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produit", referencedColumnName="id")
     * })
     */
    private $produit;

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
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }

    /**
     * @return Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * @param Facture $facture
     */
    public function setFacture($facture)
    {
        $this->facture = $facture;
    }

    /**
     * @return Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param Produit $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
    }

}

