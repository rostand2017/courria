<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="photo", indexes={@ORM\Index(name="fk_avoir", columns={"influencer"})})
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\PhotoRepository")
 */
class Photo
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
     * @ORM\Column(name="link", type="string", length=254, nullable=true)
     */
    private $link;

    /**
     * @var \Influencer
     *
     * @ORM\ManyToOne(targetEntity="Influencer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="influencer", referencedColumnName="id")
     * })
     */
    private $influencer;



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
     * Set link
     *
     * @param string $link
     *
     * @return Photo
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set influencer
     *
     * @param \HomeBundle\Entity\Influencer $influencer
     *
     * @return Photo
     */
    public function setInfluencer(\HomeBundle\Entity\Influencer $influencer = null)
    {
        $this->influencer = $influencer;

        return $this;
    }

    /**
     * Get influencer
     *
     * @return \HomeBundle\Entity\Influencer
     */
    public function getInfluencer()
    {
        return $this->influencer;
    }
}
