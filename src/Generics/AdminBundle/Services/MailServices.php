<?php

namespace Generics\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Entity\File;
use Generics\CoreBundle\Entity\Contact;

class MailServices implements DAOServices
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
     * @param $sujet
     * @param $to
     * @param $from
     * @param Contact $contact
     */
    public function sendMail($sujet, $to, $from, $contact)
    {
        $le_corps = "<b>De : </b>".$contact->getNom()."<br />";
        $le_corps .= "<i>Mail : </i>".$contact->getMail()."<br />";
        $le_corps .= "<i><u>Sujet : </u></i>".$contact->getSujet()."<br />";
        $le_corps .= "Message : <br />".$contact->getTexte()."<br />";

        $message = \Swift_Message::newInstance()
            ->setSubject($sujet)
            ->setFrom($from)
            ->setTo($to)
            ->setContentType('text/html')
            ->setBody($le_corps);
        $this->get('mailer')->send($message);
    }

    /**
     * @param Contact $element
     */
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

    public function find($array)
    {
        // TODO: Implement find() method.
    }

    public function findAll()
    {
        $liste = $this->em->getRepository('GenericsCoreBundle:Contact')->findall();
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
}