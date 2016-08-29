<?php
/**
 * Created by PhpStorm.
 * User: Benoît
 * Date: 17/06/2016
 * Time: 22:54
 */

namespace Axia\UserBiblioBundle\Twig;

use Axia\BiblioBundle\Entity\Element;
use Axia\UserBiblioBundle\Entity\Biblio;
use Axia\UserBiblioBundle\Services\BiblioServices;

class ElementFilter extends \Twig_Extension
{
    private $biblioServices;

    /**
     * ListeFilter constructor.
     * @param BiblioServices $biblioServices
     */
    public function __construct(BiblioServices $biblioServices)
    {
        $this->biblioServices = $biblioServices;
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

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('has_a_voir', array($this, 'has_a_voir')),
            new \Twig_SimpleFilter('get_item', array($this, 'get_item')),
        );
    }

    public function getName()
    {
        return 'ElementFilter';
    }
}