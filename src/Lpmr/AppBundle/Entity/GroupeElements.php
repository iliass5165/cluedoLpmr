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
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="groupeElement")
     * @ORM\JoinColumn(name="element_id", referencedColumnName="id")
     */
    private $element;

    /**
     * @ORM\ManyToOne(targetEntity="Lpmr\UserBundle\Entity\Groupe", inversedBy="groupeElement")
     * @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     */
    private $groupe;

    /**
     * @var bool
     *
     * @ORM\Column(name="selected", type="boolean", nullable=true)
     */
    private $selected;

    /**
     * @var bool
     *
     * @ORM\Column(name="scanned", type="boolean", nullable=true)
     */
    private $scanned;


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
     * @return boolean
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set element
     *
     * @param \Lpmr\AppBundle\Entity\Element $element
     *
     * @return GroupeElements
     */
    public function setElement(\Lpmr\AppBundle\Entity\Element $element = null)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return \Lpmr\AppBundle\Entity\Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Set groupe
     *
     * @param \Lpmr\UserBundle\Entity\Groupe $groupe
     *
     * @return GroupeElements
     */
    public function setGroupe(\Lpmr\UserBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Lpmr\UserBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set scanned
     *
     * @param boolean $scanned
     *
     * @return GroupeElements
     */
    public function setScanned($scanned)
    {
        $this->scanned = $scanned;

        return $this;
    }

    /**
     * Get scanned
     *
     * @return boolean
     */
    public function getScanned()
    {
        return $this->scanned;
    }
}
