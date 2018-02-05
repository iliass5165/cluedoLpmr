<?php

namespace Lpmr\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Element
 *
 * @ORM\Table(name="element", indexes={@ORM\Index(name="FK_categorie_element_id", columns={"FK_categorie_element_id"})})
 * @ORM\Entity
 */
class Element
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var \CategorieElement
     *
     * @ORM\ManyToOne(targetEntity="CategorieElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FK_categorie_element_id", referencedColumnName="id")
     * })
     */
    private $fkCategorieElement;



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
     * @return Element
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
     * Set url
     *
     * @param string $url
     *
     * @return Element
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fkCategorieElement
     *
     * @param \Lpmr\AppBundle\Entity\CategorieElement $fkCategorieElement
     *
     * @return Element
     */
    public function setFkCategorieElement($fkCategorieElement)
    {
        $this->fkCategorieElement = $fkCategorieElement;

        return $this;
    }

    /**
     * Get fkCategorieElement
     *
     * @return \Lpmr\AppBundle\Entity\CategorieElement
     */
    public function getFkCategorieElement()
    {
        return $this->fkCategorieElement;
    }
}
