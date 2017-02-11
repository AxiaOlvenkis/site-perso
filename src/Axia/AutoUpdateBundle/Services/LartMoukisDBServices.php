<?php
/**
 * Created by PhpStorm.
 * User: hb
 * Date: 25/04/2016
 * Time: 10:03
 */

namespace Axia\AutoUpdateBundle\Services;

use Axia\BiblioBundle\Entity\Jeu;
use Axia\BiblioBundle\Entity\Livre;
use Axia\BiblioBundle\Entity\SaisonAnime;
use Axia\BiblioBundle\Entity\SaisonSerie;
use Axia\BiblioBundle\Entity\Type;
use Axia\BiblioBundle\Entity\Personne;
use Axia\BiblioBundle\Entity\Editeur;
use Axia\BiblioBundle\Entity\Manga;
use Axia\BiblioBundle\Entity\Comics;
use Axia\BiblioBundle\Entity\BD;
use Axia\BiblioBundle\Entity\Tag;
use Axia\BiblioBundle\Entity\Film;
use Axia\BiblioBundle\Entity\Anime;
use Axia\BiblioBundle\Entity\Serie;
use Axia\BiblioBundle\Entity\Collection;
use Axia\BiblioBundle\Form\CollectionType;
use Axia\BiblioBundle\Form\JeuType;
use Axia\BiblioBundle\Form\LivreType;
use Axia\BiblioBundle\Form\TypeType;
use Axia\BiblioBundle\Form\PersonneType;
use Axia\BiblioBundle\Form\EditeurType;
use Axia\BiblioBundle\Form\MangaType;
use Axia\BiblioBundle\Form\BDType;
use Axia\BiblioBundle\Form\ComicsType;
use Axia\BiblioBundle\Form\FilmType;
use Axia\BiblioBundle\Form\SerieType;
use Axia\BiblioBundle\Form\AnimeType;
use Axia\BiblioBundle\Form\TagType;

class LartMoukisDBServices
{
    public $generalService;
    private $key_api;
    const DBAPIURL = "http://www.lartmoukis.fr/api/";
    const DBELEMENTS = "elements/";
    const DBELEMENT = "element/";
    const DBEDITOR = "editor/";
    const DBEDITORS = "editors/";
    const DBPEOPLE = "personne/";
    const DBPEOPLES = "personnes/";

    /**
     * LartMoukisDBServices constructor.
     * @param GeneralServices $generalService
     * @param $key_api
     */
    public function __construct($generalService, $key_api)
    {
        $this->generalService = $generalService;
        $this->key_api = $key_api;
    }

    public function search($key, $nom = '', $edit = '', $add = '', $type = '', $id ='')
    {
        $url = $this->get_url($key);
        $data = $this->format_array_search($nom, $edit, $add, $type, $id);
        $liste = $this->generalService->callAPI('GET', $url, $data);
        $liste = json_decode($liste,true);
        return $liste;
    }

    public function get_url($key)
    {
        $url = self::DBAPIURL.$key;
        return $url;
    }

    public function format_array_search($nom = '', $edit = '', $add = '', $type = '', $id ='')
    {
        $data = array(
                'apikey' => $this->key_api
        );
        if($nom != ''):
            $data['nom'] = $nom;
        endif;
        if($edit != ''):
            $data['edit'] = new \DateTime($edit);
        endif;
        if($add != ''):
            $data['add'] = new \DateTime($add);
        endif;
        if($type != ''):
            $data['type'] = $type;
        endif;
        if($id != ''):
            $data['id'] = $id;
        endif;
        return $data;
    }
}