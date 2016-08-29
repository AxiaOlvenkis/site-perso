<?php

namespace Generics\AdminBundle\Services;

use Doctrine\ORM\EntityManager;

class AlerteServices implements DAOServices
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
        $liste = $this->em->getRepository('GenericsAdminBundle:Alerte')->findall();
        return $liste;
    }

    public function findOneById($id)
    {
        // TODO: Implement findOneById() method.
    }

    public function findOne($array)
    {
        // TODO: Implement findOne() method.
    }

    public function save($element)
    {
        // TODO: Implement save() method.
    }

    public function delete($element)
    {
        $this->em->remove($element);
        $this->em->flush();
    }
}