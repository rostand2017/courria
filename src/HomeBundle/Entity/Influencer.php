<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Influencer
 *
 * @ORM\Table(name="influencer", indexes={@ORM\Index(name="fk_avoir", columns={"par_id"})})
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string", length=254, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=254, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=254, nullable=true)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=254, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=254, nullable=true)
     */
    private $gender;

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
     * @ORM\Column(name="paymenttype", type="string", length=254, nullable=true)
     */
    private $paymenttype;

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
     * @var \Partnershiptype
     *
     * @ORM\ManyToOne(targetEntity="Partnershiptype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="par_id", referencedColumnName="id")
     * })
     */
    private $par;



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
     * Set par
     *
     * @param \HomeBundle\Entity\Partnershiptype $par
     *
     * @return Influencer
     */
    public function setPar(\HomeBundle\Entity\Partnershiptype $par = null)
    {
        $this->par = $par;

        return $this;
    }

    /**
     * Get par
     *
     * @return \HomeBundle\Entity\Partnershiptype
     */
    public function getPar()
    {
        return $this->par;
    }
}
