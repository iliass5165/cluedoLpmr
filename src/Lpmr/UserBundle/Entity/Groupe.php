<?php

namespace Lpmr\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="nom", type="string", length=30, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=true)
     */
    private $token;

    /**
    * @ORM\OneToMany(targetEntity="Etudiant", mappedBy="groupe")
    */
    private $etudiants;

    /**
    * @ORM\OneToMany(targetEntity="Lpmr\AppBundle\Entity\GroupeElements", mappedBy="groupe")
    */
    private $groupeElement;

     /**
     * @var integer
     *
     * @ORM\Column(name="activated", type="boolean", nullable=false)
     */
    private $activated;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="launched_at", type="integer", nullable=true)
     */
    private $launchedAt;

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

    /**
    * Constructor
    */
   public function __construct()
   {
       $this->etudiants = new \Doctrine\Common\Collections\ArrayCollection();
   }

   /**
    * Add etudiant
    *
    * @param \Lpmr\UserBundle\Entity\Etudiant $etudiant
    *
    * @return Groupe
    */
   public function addEtudiant(\Lpmr\UserBundle\Entity\Etudiant $etudiant)
   {
       $this->etudiants[] = $etudiant;
       $etudiant->setGroupe($this);
       return $this;
   }

   /**
    * Remove rencontre
    *
    * @param \Lpmr\UserBundle\Entity\Etudiant $etudiant
    */
   public function removeEtudiant(\Lpmr\UserBundle\Entity\Etudiant $etudiant)
   {
       $this->etudiants->removeElement($etudiant);
   }

   /**
    * Get etudiants
    *
    * @return \Doctrine\Common\Collections\Collection
    */
   public function getEtudiants()
   {
       return $this->etudiants;
   }

/**
     * @return string
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

/**
     * @param string $token
     *
     * @return Groupe
     */
    public function setToken($token)
    {
        $this->token = $token;
    }




    /**
     * Add groupeElement
     *
     * @param \Lpmr\AppBundle\Entity\GroupeElements $groupeElement
     *
     * @return Groupe
     */
    public function addGroupeElement(\Lpmr\AppBundle\Entity\GroupeElements $groupeElement)
    {
        $this->groupeElement[] = $groupeElement;

        return $this;
    }

    /**
     * Remove groupeElement
     *
     * @param \Lpmr\AppBundle\Entity\GroupeElements $groupeElement
     */
    public function removeGroupeElement(\Lpmr\AppBundle\Entity\GroupeElements $groupeElement)
    {
        $this->groupeElement->removeElement($groupeElement);
    }

    /**
     * Get groupeElement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupeElement()
    {
        return $this->groupeElement;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return Groupe
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return integer
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @ORM\PrePersist
     */
    function onPrePersist() {
        // set default date
        $this->activated = false;
    }

    

    /**
     * Set launchedAt
     *
     * @param integer $launchedAt
     *
     * @return Groupe
     */
    public function setLaunchedAt($launchedAt)
    {
        $this->launchedAt = $launchedAt;

        return $this;
    }

    /**
     * Get launchedAt
     *
     * @return integer
     */
    public function getLaunchedAt()
    {
        return $this->launchedAt;
    }
}
