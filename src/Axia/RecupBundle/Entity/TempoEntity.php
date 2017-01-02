<?php

namespace Axia\RecupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TempoEntity
 *
 * @ORM\Table(name="tempo_entity")
 * @ORM\Entity(repositoryClass="Axia\RecupBundle\Repository\TempoEntityRepository")
 */
class TempoEntity
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
     * @var string
     *
     * @ORM\Column(name="stringID", type="string", length=255)
     */
    private $stringID;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var bool
     *
     * @ORM\Column(name="refuse", type="boolean")
     */
    private $refuse;


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
     * Set stringID
     *
     * @param string $stringID
     *
     * @return TempoEntity
     */
    public function setStringID($stringID)
    {
        $this->stringID = $stringID;

        return $this;
    }

    /**
     * Get stringID
     *
     * @return string
     */
    public function getStringID()
    {
        return $this->stringID;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TempoEntity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set refuse
     *
     * @param boolean $refuse
     *
     * @return TempoEntity
     */
    public function setRefuse($refuse)
    {
        $this->refuse = $refuse;

        return $this;
    }

    /**
     * Get refuse
     *
     * @return bool
     */
    public function getRefuse()
    {
        return $this->refuse;
    }
}

