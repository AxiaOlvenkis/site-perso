<?php

namespace Generics\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Entity\File;

class FileServices
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

    /**
     * @param File $file
     * @param $dossier
     */
    public function saveFile($file, $dossier){
        $file->setDossier($dossier);
        $file->upload();
        $this->em->persist($file);
        $this->em->flush();
    }
}