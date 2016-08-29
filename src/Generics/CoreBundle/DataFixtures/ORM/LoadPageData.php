<?php
/**
 * Created by PhpStorm.
 * User: Benoît
 * Date: 12/06/2016
 * Time: 11:35
 */

namespace Axia\BiblioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Axia\BiblioBundle\Entity\Type;
use Generics\CoreBundle\Entity\Page;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadPageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $accueil = new Page();
        $accueil->setTitre('Accueil');
        $accueil->setLink('accueil');
        $accueil->setTexte('ceci est la page d\'accueil');

        $who = new Page();
        $who->setTitre('Qui suis-je');
        $who->setLink('qui-suis-je');
        $who->setTexte('Je suis quelqu\'un ! Si si !');

        $leg = new Page();
        $leg->setTitre('Mentions Légales');
        $leg->setLink('mentions-legales');
        $leg->setTexte('A venir');

        $manager->persist($accueil);
        $manager->persist($who);
        $manager->persist($leg);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}