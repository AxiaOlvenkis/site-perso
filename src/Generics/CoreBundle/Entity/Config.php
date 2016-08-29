<?php

namespace Generics\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="site_config")
 * @ORM\Entity(repositoryClass="Generics\CoreBundle\Repository\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="str_id", type="string", length=255, unique=true)
     */
    private $strId;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;


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
     * Set strId
     *
     * @param string $strId
     *
     * @return Config
     */
    public function setStrId($strId)
    {
        $this->strId = $strId;

        return $this;
    }

    /**
     * Get strId
     *
     * @return string
     */
    public function getStrId()
    {
        return $this->strId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Config
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

