<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concerne
 *
 * @ORM\Table(name="concerne", indexes={@ORM\Index(name="fk_concerne", columns={"activity"}), @ORM\Index(name="fk_est_concerne", columns={"influencer"})})
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
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity", referencedColumnName="id")
     * })
     */
    private $activity;

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
     * Set activity
     *
     * @param \HomeBundle\Entity\Activity $activity
     *
     * @return Concerne
     */
    public function setActivity(\HomeBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \HomeBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set influencer
     *
     * @param \HomeBundle\Entity\Influencer $influencer
     *
     * @return Concerne
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
