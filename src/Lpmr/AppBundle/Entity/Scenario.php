<?php

namespace Lpmr\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scenario
 *
 * @ORM\Table(name="scenario")
 * @ORM\Entity
 */
class Scenario
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Element", mappedBy="fkScenario")
     */
    private $fkElement;

    /**
      * @ORM\Column(name="selectedScenario", type="boolean", options={"default":"0"})
     */
    private $selectedScenario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fkElement = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Scenario
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Scenario
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add fkElement
     *
     * @param \Lpmr\AppBundle\Entity\Element $fkElement
     *
     * @return Scenario
     */
    public function addFkElement(\Lpmr\AppBundle\Entity\Element $fkElement)
    {
        $this->fkElement[] = $fkElement;
        
        return $this;
    }

    /**
     * Remove fkElement
     *
     * @param \Lpmr\AppBundle\Entity\Element $fkElement
     */
    public function removeFkElement(\Lpmr\AppBundle\Entity\Element $fkElement)
    {
        $this->fkElement->removeElement($fkElement);
    }

    /**
     * Get fkElement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFkElement()
    {
        return $this->fkElement;
    }

    /**
     * Set selectedScenario.
     *
     * @param bool $selectedScenario
     *
     * @return Scenario
     */
    public function setSelectedScenario($selectedScenario)
    {
        $this->selectedScenario = $selectedScenario;

        return $this;
    }

    /**
     * Get selectedScenario.
     *
     * @return bool
     */
    public function getSelectedScenario()
    {
        return $this->selectedScenario;
    }
}
