<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Files
 *
 * @ORM\Table(name="files", indexes={@ORM\Index(name="fk_files_courrier", columns={"courrier"})})
 * @ORM\Entity()
 */
class Files
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
     * @ORM\Column(name="path", type="string", length=254, nullable=false)
     */
    private $path;

    /**
     * @var Courrier
     *
     * @ORM\ManyToOne(targetEntity="Courrier", inversedBy="courrier", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="courrier", referencedColumnName="id")
     * })
     */
    private $courrier;

    /**
     * Observation constructor.
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return Courrier
     */
    public function getCourrier()
    {
        return $this->courrier;
    }

    /**
     * @param Courrier $courrier
     */
    public function setCourrier($courrier)
    {
        $this->courrier = $courrier;
    }

}

