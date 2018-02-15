<?php

namespace Lpmr\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupeElements
 *
 * @ORM\Table(name="groupe_element")
 * @ORM\Entity(repositoryClass="Lpmr\UserBundle\Repository\GroupeElementsRepository")
 */
class GroupeElements
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="elementId")
     * @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     */
    private $elementId;

    /**
     * @ORM\ManyToOne(targetEntity="Lpmr\UserBundle\Entity\Groupe", inversedBy="groupeId")
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     */
    private $groupeId;

    /**
     * @var bool
     *
     * @ORM\Column(name="selected", type="boolean")
     */
    private $selected;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set groupeId
     *
     * @param integer $groupeId
     *
     * @return GroupeElements
     */
    public function setGroupeId($groupeId)
    {
        $this->groupeId = $groupeId;

        return $this;
    }

    /**
     * Get groupeId
     *
     * @return int
     */
    public function getGroupeId()
    {
        return $this->groupeId;
    }

    /**
     * Set elementId
     *
     * @param integer $elementId
     *
     * @return GroupeElements
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;

        return $this;
    }

    /**
     * Get elementId
     *
     * @return int
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * Set selected
     *
     * @param boolean $selected
     *
     * @return GroupeElements
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return bool
     */
    public function getSelected()
    {
        return $this->selected;
    }
}
