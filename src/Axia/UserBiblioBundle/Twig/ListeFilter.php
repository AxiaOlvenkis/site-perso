<?php
/**
 * Created by PhpStorm.
 * User: Benoît
 * Date: 17/06/2016
 * Time: 22:54
 */

namespace Axia\UserBiblioBundle\Twig;

use Axia\BiblioBundle\Entity\Element;
use Axia\UserBiblioBundle\Services\BiblioServices;

class ListeFilter extends \Twig_Extension
{
    /**
     * @param $date
     * @return string
     */
    public function is_paru($date)
    {
        $ajd = new \DateTime();

        if($date > $ajd)
        {
            return "<i class=\"fa fa-square-o\"></i>";
        }
        else
        {
            return "<i class=\"fa fa-check-square-o\"></i>";
        }
    }

    /**
     * @param $boolean
     * @return string
     */
    public function get_checked($boolean)
    {
        if(!$boolean)
        {
            return "<i class=\"fa fa-square-o\"></i>";
        }
        else
        {
            return "<i class=\"fa fa-check-square-o\"></i>";
        }
    }

    public function get_note($note)
    {
        $str_note = "";
        if($note < 0)
        {
            return "non noté";
        }

        for($i = 1; $i <= $note; $i++)
        {
            $str_note .= "<i class=\"fa fa-star\"></i>";
        }

        if($note != 10)
        {
            for($i; $i <= 10; $i++)
            {
                $str_note .= "<i class=\"fa fa-star-o\"></i>";
            }
        }

        return $str_note;
    }

    /**
     * @param Element $element
     * @return string
     */
    public function get_status($element)
    {
        $fini = $element->getFini();

        if($fini):
            return 'Terminé';
        else:
            $ajd = new \DateTime();

            if($element->getDateParution() > $ajd)
            {
                return "A Venir";
            }
            else
            {
                return "En Cours";
            }
        endif;
    }

    /**
     * @param \DateTime $date
     * @return string
     */
    public function get_saison($date)
    {
        $mois = $date->format('m');
        $reponse = '';

        if($mois < 3 && $mois > 0):
            $reponse = "<span class='label label-danger'>Hiver</span>";
        elseif($mois < 6):
            $reponse = "<span class='label label-info'>Printemps</span>";
        elseif($mois < 9):
            $reponse = "<span class='label label-default'>Eté</span>";
        elseif($mois < 12):
            $reponse = "<span class='label label-success'>Automne</span>";
        else:
            $reponse = 'Season not found';
        endif;

        return $reponse;
    }


    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isParu', array($this, 'is_paru')),
            new \Twig_SimpleFilter('getChecked', array($this, 'get_checked')),
            new \Twig_SimpleFilter('getNote', array($this, 'get_note')),
            new \Twig_SimpleFilter('getStatus', array($this, 'get_status')),
            new \Twig_SimpleFilter('getSeason', array($this, 'get_saison')),
        );
    }

    public function getName()
    {
        return 'ListeFilter';
    }
}