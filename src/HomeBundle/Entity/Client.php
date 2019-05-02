<?php

namespace HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client
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
     * @ORM\Column(name="cni", type="string", length=254, nullable=true)
     */
    private $cni;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=254, nullable=true)
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=254, nullable=true)
     */
    private $mdp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdat", type="datetime", nullable=true)
     */
    private $createdat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Concert", inversedBy="cli")
     * @ORM\JoinTable(name="reservation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="cli_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="con_id", referencedColumnName="id")
     *   }
     * )
     */
    private $con;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->con = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * Set cni
     *
     * @param string $cni
     *
     * @return Client
     */
    public function setCni($cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get cni
     *
     * @return string
     */
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Client
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Client
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Client
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
     * Set mdp
     *
     * @param string $mdp
     *
     * @return Client
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     *
     * @return Client
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
     * Add con
     *
     * @param \HomeBundle\Entity\Concert $con
     *
     * @return Client
     */
    public function addCon(\HomeBundle\Entity\Concert $con)
    {
        $this->con[] = $con;

        return $this;
    }

    /**
     * Remove con
     *
     * @param \HomeBundle\Entity\Concert $con
     */
    public function removeCon(\HomeBundle\Entity\Concert $con)
    {
        $this->con->removeElement($con);
    }

    /**
     * Get con
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCon()
    {
        return $this->con;
    }
}
