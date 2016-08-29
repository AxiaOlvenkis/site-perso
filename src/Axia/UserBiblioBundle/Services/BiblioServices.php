<?php

namespace Axia\UserBiblioBundle\Services;

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
            $liste = $this->em->getRepository('AxiaUserBiblioBundle:Biblio')->findAVenir($type, $this->user);
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