<?php

namespace Generics\CoreBundle\Services;

use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Services\DAOServices;

class RealisationServices implements DAOServices
{
    /**
     * @var EntityManager em
     */
    private $em;

    /**
     * TypeService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    public function find($array)
    {
        // TODO: Implement find() method.
    }

    public function findAll()
    {
        $liste = $this->em->getRepository('GenericsCoreBundle:Realisation')->findall();
        return $liste;
    }

    public function findOneById($id)
    {
        // TODO: Implement findOneById() method.
    }

    public function findOne($array)
    {
        $page = $this->em->getRepository('GenericsCoreBundle:Realisation')->findOneBy($array);
        return $page;
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