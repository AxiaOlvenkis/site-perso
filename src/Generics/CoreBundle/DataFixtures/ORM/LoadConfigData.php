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
use Generics\CoreBundle\Entity\Config;
use Generics\CoreBundle\Entity\Page;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadConfigData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $config_maj_anime = new Config();
        $config_maj_anime->setStrId("maj_biblio_anime");
        $config_maj_anime->setValue(null);
        $manager->persist($config_maj_anime);

        $config_maj_serie = new Config();
        $config_maj_serie->setStrId("maj_biblio_serie");
        $config_maj_serie->setValue(null);
        $manager->persist($config_maj_serie);

        $config_maj_livre = new Config();
        $config_maj_livre->setStrId("maj_biblio_livre");
        $config_maj_livre->setValue(null);
        $manager->persist($config_maj_livre);

        $config_maj_jeu = new Config();
        $config_maj_jeu->setStrId("maj_biblio_jeu");
        $config_maj_jeu->setValue(null);
        $manager->persist($config_maj_jeu);

        $config_maj_film = new Config();
        $config_maj_film->setStrId("maj_biblio_film");
        $config_maj_film->setValue(null);
        $manager->persist($config_maj_film);

        $config_maj_bd = new Config();
        $config_maj_bd->setStrId("maj_biblio_bd");
        $config_maj_bd->setValue(null);
        $manager->persist($config_maj_bd);

        $config_maj_comics = new Config();
        $config_maj_comics->setStrId("maj_biblio_comics");
        $config_maj_comics->setValue(null);
        $manager->persist($config_maj_comics);

        $config_maj_manga = new Config();
        $config_maj_manga->setStrId("maj_biblio_manga");
        $config_maj_manga->setValue(null);
        $manager->persist($config_maj_manga);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}