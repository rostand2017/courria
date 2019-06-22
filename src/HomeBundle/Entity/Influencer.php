<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Influencer
 *
 * @ORM\Table(name="influencer", indexes={@ORM\Index(name="fk_association2", columns={"partnership"})})
 * @ORM\Entity(repositoryClass="HomeBundle\Repository\InfluencerRepository")
 */
class Influencer
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
     * @ORM\Column(name="name", type="string", length=254, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=254, nullable=false)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=254, nullable=false)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=254, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=254, nullable=false)
     */
    private $gender;

    /**
     * @var integer
     *
     * @ORM\Column(name="old", type="integer", nullable=false)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="facebooklink", type="string", length=254, nullable=true)
     */
    private $facebooklink;

    /**
     * @var string
     *
     * @ORM\Column(name="twitterlink", type="string", length=254, nullable=true)
     */
    private $twitterlink;

    /**
     * @var string
     *
     * @ORM\Column(name="instagramlink", type="string", length=254, nullable=true)
     */
    private $instagramlink;

    /**
     * @var string
     *
     * @ORM\Column(name="snapchatlink", type="string", length=254, nullable=true)
     */
    private $snapchatlink;

    /**
     * @var string
     *
     * @ORM\Column(name="paymenttype", type="string", length=254, nullable=false)
     */
    private $paymenttype;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=254, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedat", type="datetime", nullable=true)
     */
    private $updatedat;

    /**
     * @var Partnershiptype
     *
     * @ORM\ManyToOne(targetEntity="Partnershiptype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partnership", referencedColumnName="id", nullable=false)
     * })
     */
    private $partnership;


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
     * Set name
     *
     * @param string $name
     *
     * @return Influencer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Influencer
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Influencer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Influencer
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Influencer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Influencer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set old
     *
     * @param integer $old
     *
     * @return Influencer
     */
    public function setOld($old)
    {
        $this->old = $old;

        return $this;
    }

    /**
     * Get old
     *
     * @return integer
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * Set facebooklink
     *
     * @param string $facebooklink
     *
     * @return Influencer
     */
    public function setFacebooklink($facebooklink)
    {
        $this->facebooklink = $facebooklink;

        return $this;
    }

    /**
     * Get facebooklink
     *
     * @return string
     */
    public function getFacebooklink()
    {
        return $this->facebooklink;
    }

    /**
     * Set twitterlink
     *
     * @param string $twitterlink
     *
     * @return Influencer
     */
    public function setTwitterlink($twitterlink)
    {
        $this->twitterlink = $twitterlink;

        return $this;
    }

    /**
     * Get twitterlink
     *
     * @return string
     */
    public function getTwitterlink()
    {
        return $this->twitterlink;
    }

    /**
     * Set instagramlink
     *
     * @param string $instagramlink
     *
     * @return Influencer
     */
    public function setInstagramlink($instagramlink)
    {
        $this->instagramlink = $instagramlink;

        return $this;
    }

    /**
     * Get instagramlink
     *
     * @return string
     */
    public function getInstagramlink()
    {
        return $this->instagramlink;
    }

    /**
     * Set snapchatlink
     *
     * @param string $snapchatlink
     *
     * @return Influencer
     */
    public function setSnapchatlink($snapchatlink)
    {
        $this->snapchatlink = $snapchatlink;

        return $this;
    }

    /**
     * Get snapchatlink
     *
     * @return string
     */
    public function getSnapchatlink()
    {
        return $this->snapchatlink;
    }

    /**
     * Set paymenttype
     *
     * @param string $paymenttype
     *
     * @return Influencer
     */
    public function setPaymenttype($paymenttype)
    {
        $this->paymenttype = $paymenttype;

        return $this;
    }

    /**
     * Get paymenttype
     *
     * @return string
     */
    public function getPaymenttype()
    {
        return $this->paymenttype;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     *
     * @return Influencer
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
     * Set updatedat
     *
     * @param \DateTime $updatedat
     *
     * @return Influencer
     */
    public function setUpdatedat($updatedat)
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    /**
     * Get updatedat
     *
     * @return \DateTime
     */
    public function getUpdatedat()
    {
        return $this->updatedat;
    }

    /**
     * Set partnership
     *
     * @param \HomeBundle\Entity\Partnershiptype $partnership
     *
     * @return Influencer
     */
    public function setPartnership(\HomeBundle\Entity\Partnershiptype $partnership = null)
    {
        $this->partnership = $partnership;

        return $this;
    }

    /**
     * Get partnership
     *
     * @return \HomeBundle\Entity\Partnershiptype
     */
    public function getPartnership()
    {
        return $this->partnership;
    }
}
