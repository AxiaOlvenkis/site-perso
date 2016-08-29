<?php

namespace Generics\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Services\DAOServices;

class UserServices implements DAOServices
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
        return $this->em->getRepository('GenericsUserBundle:User')->findall();
    }

    public function findOneById($id)
    {
        // TODO: Implement findOneById() method.
    }

    public function findOne($array)
    {
        return $this->em->getRepository('GenericsUserBundle:User')->findOneBy($array);
    }

    public function findOneByPseudo($pseudo)
    {
        return $this->em->getRepository('GenericsUserBundle:User')->findOneBy(array('username' => $pseudo));
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