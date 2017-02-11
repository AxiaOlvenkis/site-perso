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

class APIMovieDBServices
{
    public $elementService;
    const MOVIEDBAPIURL = "https://api.themoviedb.org/3/";
    const MOVIEDB_SEARCH_KW = "search/";
    const MOVIEDBURL = "https://www.themoviedb.org/";

    /**
     * SearchAPIServices constructor.
     * @param $elementService
     */
    public function __construct($elementService)
    {
        $this->elementService = $elementService;
    }

    public function get_fiche_url($type)
    {
        if($type == 'Film'){
            return self::MOVIEDBURL.'movie/';
        }
        elseif($type == 'Serie' || $type == 'Anime'){
            return self::MOVIEDBURL.'tv/';
        }
        return '';
    }

    public function get_search_url($type)
    {
        if($type == 'Film'):
            $key = 'movie';
        elseif($type == 'Serie' || $type == 'Anime'):
            $key = 'tv';
        endif;
        $url = self::MOVIEDBAPIURL.self::MOVIEDB_SEARCH_KW.$key;
        return $url;
    }

    public function get_search_id_url($id, $type){
        if($type == 'Film'):
            $key = 'movie';
        elseif($type == 'Serie' || $type == 'Anime'):
            $key = 'tv';
        endif;
        return self::MOVIEDBAPIURL.$key.'/'.$id;
    }

    public function format_array_search($keyAPI, $nom = '', $year = '')
    {
        $data = array(
                'api_key' => $keyAPI,
                'language' => 'fr'
        );
        if($nom != ''):
            $data['query'] = $nom;
        endif;
        if($year != ''):
            $data['primary_release_year'] = $year;
        endif;
        return $data;
    }

    public function search_by_id($id, $type){
        if($type == 'Film'):
            $key = 'movie';
        elseif($type == 'Serie' || $type == 'Anime'):
            $key = 'tv';
        endif;
        $url = self::MOVIEDBAPIURL.$key.'/'.$id;
        return $url;
    }

    public function create_film($tab, $film = null, $generalService = null)
    {
        if($film == null):
            $film = new Film();
        endif;
        $film->setTitre($tab['title']);
        $film->setResume($tab['overview']);
        $date = new \DateTime($tab['release_date']);
        $film->setDateParution($date);
        $film->setApiKey($tab['id']);
        $film->setFiche(self::MOVIEDBURL.'movie/'.$film->getApiKey());

        return $film;
    }

    /**
     * @param $tab
     * @param null $serie
     * @param GeneralServices $generalService
     * @return Serie|null
     */
    public function create_serie($tab, $serie = null, $generalService = null)
    {
        if($serie == null):
            $serie = new Serie();
            $serie->setFini(false);
        endif;
        $serie->setTitre($tab['name']);
        $serie->setResume($tab['overview']);
        $date = new \DateTime($tab['first_air_date']);
        $serie->setDateParution($date);
        $serie->setApiKey($tab['id']);
        $serie->setFiche(self::MOVIEDBURL.'tv/'.$serie->getApiKey());

        $serie = $this->create_saison($tab['seasons'], $serie, $generalService);
        return $serie;
    }

    /**
     * @param array $array
     * @param Serie $serie
     * @param GeneralServices $generalService
     * @return mixed
     */
    public function create_saison($array, $serie, $generalService){
        $ex_seasons = $serie->getSaisons();

        foreach($array as $api_season):
            if($ex_seasons == null || $this->saison_exist($api_season['season_number'],$ex_seasons) == false):
                $saison = new SaisonSerie();
                $saison->setSerie($serie);
                $saison->setNumero($api_season['season_number']);
                $serie->addSaison($saison);
            else:
                $saison = $serie->getSaisonByNumber($api_season['season_number']);
            endif;

            if(array_key_exists($api_season['season_number'] + 1, $array)):
                $saison->setFini(true);
            else:
                $saison->setFini(false);
            endif;
            $saison->setNbEpisodes($api_season['episode_count']);
        endforeach;
        return $serie;
    }

    /**
 * @param $tab
 * @param null $serie
 * @param GeneralServices $generalService
 * @return Serie|null
 */
    public function create_anime($tab, $anime = null, $generalService = null)
    {
        if($anime == null):
            $anime = new Anime();
            $anime->setFini(false);
        endif;
        $anime->setTitre($tab['name']);
        $anime->setResume($tab['overview']);
        $date = new \DateTime($tab['first_air_date']);
        $anime->setDateParution($date);
        $anime->setApiKey($tab['id']);
        $anime->setFiche(self::MOVIEDBURL.'tv/'.$anime->getApiKey());

        if(isset($tab['seasons'])):
            $anime = $this->create_saison_anime($tab['seasons'], $anime, $generalService);
        endif;

        return $anime;
    }

    /**
     * @param array $array
     * @param Anime $serie
     * @param GeneralServices $generalService
     * @return mixed
     */
    public function create_saison_anime($array, $serie, $generalService){
        $ex_seasons = $serie->getSaisons();

        foreach($array as $api_season):
            if($ex_seasons == null || $this->saison_exist($api_season['season_number'],$ex_seasons) == false):
                $saison = new SaisonAnime();
                $saison->setAnime($serie);
                $saison->setNumero($api_season['season_number']);
                $serie->addSaison($saison);
            else:
                $saison = $serie->getSaisonByNumber($api_season['season_number']);
            endif;

            if(array_key_exists($api_season['season_number'] + 1, $array)):
                $saison->setFini(true);
            else:
                $saison->setFini(false);
            endif;
            $saison->setNbEpisodes($api_season['episode_count']);
        endforeach;
        return $serie;
    }

    private function saison_exist($api_number, $serie_season){
        foreach($serie_season as $saison){
            if($saison->getNumero() == $api_number):
                return true;
            endif;
        }

        return false;
    }
}