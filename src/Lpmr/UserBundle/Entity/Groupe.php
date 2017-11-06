<?php

namespace Lpmr\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity
 */
class Groupe
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
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPointGlobal", type="integer", nullable=false)
     */
    private $nbpointglobal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="annee", type="date", nullable=false)
     */
    private $annee;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable=false)
     */
    private $code;



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
     * @return Groupe
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
     * Set nbpointglobal
     *
     * @param integer $nbpointglobal
     *
     * @return Groupe
     */
    public function setNbpointglobal($nbpointglobal)
    {
        $this->nbpointglobal = $nbpointglobal;

        return $this;
    }

    /**
     * Get nbpointglobal
     *
     * @return integer
     */
    public function getNbpointglobal()
    {
        return $this->nbpointglobal;
    }

    /**
     * Set annee
     *
     * @param \DateTime $annee
     *
     * @return Groupe
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \DateTime
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Groupe
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }
}
