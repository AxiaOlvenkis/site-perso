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
        return $this->biblioServices->get_item($element);
    }

    /**
     * @param Biblio $biblio
     * @return int
     */
    public function has_a_voir($biblio)
    {
        return $this->biblioServices->has_a_voir($biblio);
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