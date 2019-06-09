<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concerne
 *
 * @ORM\Table(name="concerne", indexes={@ORM\Index(name="fk_concerne", columns={"activity_id"}), @ORM\Index(name="fk_concernee", columns={"influencer_id"})})
 * @ORM\Entity
 */
class Concerne
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=10, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     * })
     */
    private $activity;

    /**
     * @var \Influencer
     *
     * @ORM\ManyToOne(targetEntity="Influencer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="influencer_id", referencedColumnName="id")
     * })
     */
    private $influencer;



    /**
     * Get id
     *
     * @return string
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
