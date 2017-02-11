<?php

namespace Axia\UserBiblioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Biblio
 *
 * @ORM\Table(name="user_biblio")
 * @ORM\Entity(repositoryClass="Axia\UserBiblioBundle\Repository\BiblioRepository")
 */
class Biblio
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
     * @var int
     *
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var bool
     *
     * @ORM\Column(name="vu", type="boolean")
     */
    private $vu;

    /**
     * @var bool
     *
     * @ORM\Column(name="possede", type="boolean")
     */
    private $possede;

    /**
     * @var int
     *
     * @ORM\Column(name="dernier_vu", type="integer")
     */
    private $dernierVu;

    /**
     * @var int
     *
     * @ORM\Column(name="valide", type="integer")
     */
    private $valide;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Generics\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\Livre")
     * @ORM\JoinColumn(nullable=true)
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\Manga")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manga;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\Comics")
     * @ORM\JoinColumn(nullable=true)
     */
    private $comics;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\BD")
     * @ORM\JoinColumn(nullable=true)
     */
    private $bd;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\Jeu")
     * @ORM\JoinColumn(nullable=true)
     */
    private $jeu;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\Film")
     * @ORM\JoinColumn(nullable=true)
     */
    private $film;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\SaisonSerie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $serie;

    /**
     * @ORM\ManyToOne(targetEntity="Axia\BiblioBundle\Entity\SaisonAnime")
     * @ORM\JoinColumn(nullable=true)
     */
    private $anime;

    /**
     * @var string
     *
     * @ORM\Column(name="streaming", type="string",nullable=true)
     */
    private $streaming;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dernierVu = 0;
        $this->note = 0;
        $this->possede = 0;
        $this->valide = 0;
        $this->vu = 0;
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
     * Set note
     *
     * @param integer $note
     *
     * @return Biblio
     */
    public function setNote($note)
    {
        if($note > 10):
            $this->note = 10;
        elseif($note < -1):
            $this->note = -1;
        else:
            $this->note = $note;
        endif;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set vu
     *
     * @param boolean $vu
     *
     * @return Biblio
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu
     *
     * @return boolean
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set possede
     *
     * @param boolean $possede
     *
     * @return Biblio
     */
    public function setPossede($possede)
    {
        $this->possede = $possede;

        return $this;
    }

    /**
     * Get possede
     *
     * @return boolean
     */
    public function getPossede()
    {
        return $this->possede;
    }

    /**
     * Set dernierVu
     *
     * @param integer $dernierVu
     *
     * @return Biblio
     */
    public function setDernierVu($dernierVu)
    {
        $this->dernierVu = $dernierVu;

        return $this;
    }

    /**
     * Get dernierVu
     *
     * @return integer
     */
    public function getDernierVu()
    {
        return $this->dernierVu;
    }

    /**
     * Set valide
     *
     * @param integer $valide
     *
     * @return Biblio
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return integer
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set user
     *
     * @param \Generics\UserBundle\Entity\User $user
     *
     * @return Biblio
     */
    public function setUser(\Generics\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Generics\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Biblio
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
     * Set livre
     *
     * @param \Axia\BiblioBundle\Entity\Livre $livre
     *
     * @return Biblio
     */
    public function setLivre(\Axia\BiblioBundle\Entity\Livre $livre = null)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return \Axia\BiblioBundle\Entity\Livre
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set manga
     *
     * @param \Axia\BiblioBundle\Entity\Manga $manga
     *
     * @return Biblio
     */
    public function setManga(\Axia\BiblioBundle\Entity\Manga $manga = null)
    {
        $this->manga = $manga;

        return $this;
    }

    /**
     * Get manga
     *
     * @return \Axia\BiblioBundle\Entity\Manga
     */
    public function getManga()
    {
        return $this->manga;
    }

    /**
     * Set comics
     *
     * @param \Axia\BiblioBundle\Entity\Comics $comics
     *
     * @return Biblio
     */
    public function setComics(\Axia\BiblioBundle\Entity\Comics $comics = null)
    {
        $this->comics = $comics;

        return $this;
    }

    /**
     * Get comics
     *
     * @return \Axia\BiblioBundle\Entity\Comics
     */
    public function getComics()
    {
        return $this->comics;
    }

    /**
     * Set bd
     *
     * @param \Axia\BiblioBundle\Entity\BD $bd
     *
     * @return Biblio
     */
    public function setBd(\Axia\BiblioBundle\Entity\BD $bd = null)
    {
        $this->bd = $bd;

        return $this;
    }

    /**
     * Get bd
     *
     * @return \Axia\BiblioBundle\Entity\BD
     */
    public function getBd()
    {
        return $this->bd;
    }

    /**
     * Set jeu
     *
     * @param \Axia\BiblioBundle\Entity\Jeu $jeu
     *
     * @return Biblio
     */
    public function setJeu(\Axia\BiblioBundle\Entity\Jeu $jeu = null)
    {
        $this->jeu = $jeu;

        return $this;
    }

    /**
     * Get jeu
     *
     * @return \Axia\BiblioBundle\Entity\Jeu
     */
    public function getJeu()
    {
        return $this->jeu;
    }

    /**
     * Set film
     *
     * @param \Axia\BiblioBundle\Entity\Film $film
     *
     * @return Biblio
     */
    public function setFilm(\Axia\BiblioBundle\Entity\Film $film = null)
    {
        $this->film = $film;

        return $this;
    }

    /**
     * Get film
     *
     * @return \Axia\BiblioBundle\Entity\Film
     */
    public function getFilm()
    {
        return $this->film;
    }

    /**
     * Set serie
     *
     * @param \Axia\BiblioBundle\Entity\SaisonSerie $serie
     *
     * @return Biblio
     */
    public function setSerie(\Axia\BiblioBundle\Entity\SaisonSerie $serie = null)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return \Axia\BiblioBundle\Entity\SaisonSerie
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set anime
     *
     * @param \Axia\BiblioBundle\Entity\SaisonAnime $anime
     *
     * @return Biblio
     */
    public function setAnime(\Axia\BiblioBundle\Entity\SaisonAnime $anime = null)
    {
        $this->anime = $anime;

        return $this;
    }

    /**
     * Get anime
     *
     * @return \Axia\BiblioBundle\Entity\SaisonAnime
     */
    public function getAnime()
    {
        return $this->anime;
    }

    /**
     * Set streaming
     *
     * @param string $streaming
     *
     * @return Biblio
     */
    public function setStreaming($streaming)
    {
        $this->streaming = $streaming;

        return $this;
    }

    /**
     * Get streaming
     *
     * @return string
     */
    public function getStreaming()
    {
        return $this->streaming;
    }
}
