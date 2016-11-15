<?php

namespace Axia\UserBiblioBundle\Services;

use Axia\BiblioBundle\Entity\Element;
use Axia\UserBiblioBundle\Entity\Biblio;
use Axia\UserBiblioBundle\Twig\ElementFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Services\DAOServices;
use Generics\UserBundle\Services\UserServices;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class BiblioServices implements DAOServices
{
    /**
     * @var EntityManager em
     */
    private $em;
    private $user;

    /**
     * TypeService constructor.
     * @param EntityManager $entityManager
     * @param TokenStorage $tokenStorage
     */
    public function __construct($entityManager, TokenStorage $tokenStorage, UserServices $userService)
    {
        $this->em = $entityManager;
        $this->user = $userService->findOneByPseudo('AxiaOlvenkis');
    }

    public function find($array)
    {
        $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findBy($array);
        return $liste;
    }

    public function findAll()
    {
        $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findall();
        return $liste;
    }

    public function findOneById($id)
    {
        return $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->find($id);
    }

    public function findOne($array)
    {
        $page = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findOneBy($array);
        return $page;
    }

    public function repoPerso($type, $filtre, $search = "")
    {
        $liste = array();

        if($filtre == 'fini')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findFini($type, $this->user);
        }
        elseif($filtre == 'en_cours')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findEnCours($type, $this->user);
        }
        elseif($filtre == 'liste_course')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findListeCourse($type, $this->user);
        }
        elseif($filtre == 'en_cours_a_voir')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findEnCours($type, $this->user);
            $liste = $this->array_a_voir($liste);
        }
        elseif($filtre == 'saison')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findSaison($type, $this->user);
        }
        elseif($filtre == 'a_voir')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findAVoir($type, $this->user);
        }
        elseif($filtre == 'a_venir')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findAVenir($type, $this->user);
        }
        elseif($filtre == 'a_acheter')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findAAcheter($type, $this->user);
        }
        elseif($filtre == 'a_l_affiche')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findALAffiche($type, $this->user);
        }
        elseif($filtre == 'dvd')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findDVD($this->user);
        }
        elseif($filtre == 'souhait')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findSouhait($type, $this->user);
        }
        elseif($filtre == 'SortieduMois')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findMonthBooks($this->user);
        }
        elseif($filtre == 'recherche')
        {
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->searchByTitre($type, $this->user, $search);
        }

        return $liste;
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function array_a_voir($array)
    {
        foreach ($array as $biblio)
        {
            $nb_vu = $biblio->getDernierVu();
            $nb_sortie = $this->has_a_voir($biblio);

            if($nb_vu >= $nb_sortie)
            {
                $key = array_search($biblio, $array, true);
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * @param Biblio $element
     * @return Element
     */
    public function get_item($element)
    {
        $type = $element->getType();
        $getter = 'get'.$type;

        return $element->$getter();
    }

    /**
     * @param Biblio $biblio
     * @return int
     */
    public function has_a_voir($biblio)
    {
        $element = $this->get_item($biblio);

        if($element->getFini()):
            $nb_t = $element->getNbEpisode();
        else:
            $date = $element->getDateParution()->format('Y/m/d');
            $nb_t = $this->ep_by_date($date) + 1;
            if($nb_t > $element->getNbEpisode()):
                $nb_t = $element->getNbEpisode();
            endif;
        endif;

        return $nb_t;
    }

    public function ep_by_date($date)
    {
        $date_ajd = new \DateTime();
        $date_ajd->format('Y/m/d');
        $date_deb = new \DateTime($date);

        /* on fait les calcules pour retourner le nb d'episode */
        $diff = $date_deb->diff($date_ajd);
        $nb_ep = $diff->days / 7;
        return floor($nb_ep);
    }

    public function save($element)
    {
        $this->em->persist($element);
        $this->em->flush();
    }

    public function delete($element)
    {
        $this->em->remove($element);
        $this->em->flush();
    }
}