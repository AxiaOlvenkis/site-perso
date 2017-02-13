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
use Axia\BiblioBundle\Services\ElementServices;
use Axia\BiblioBundle\Services\TagServices;

class GeneralServices
{
    private $movieKeyAPI;
    private $tagServices;
    private $elementServices;
    /**
     * @var APIMovieDBServices $movieDBServices
     */
    private $movieDBServices;

    /**
     * GeneralServices constructor.
     * @param $key_api_movie
     * @param APIMovieDBServices $movieDBServices
     * @param TagServices $tagServices
     * @param ElementServices $elementServices
     */
    public function __construct($key_api_movie, $movieDBServices, $tagServices, $elementServices)
    {
        $this->movieKeyAPI = $key_api_movie;
        $this->movieDBServices = $movieDBServices;
        $this->tagServices = $tagServices;
        $this->elementServices = $elementServices;
    }

    public function getMovieKeyAPI()
    {
        return $this->movieKeyAPI;
    }

    public function get_fiche_url($type){
        $url = '';
        if($type == 'Film' || $type == 'Serie' || $type == 'Anime'):
            $url = $this->movieDBServices->get_fiche_url($type);
        endif;
        return $url;
    }

    public function get_api_url($type)
    {
        $url = '';
        if($type == 'Film' || $type == 'Serie' || $type == 'Anime'):
            $url = $this->movieDBServices->get_search_url($type);
        endif;
        return $url;
    }

    public function get_search_id_url($key, $type)
    {
        $url = '';
        if($type == 'Film' || $type == 'Serie' || $type == 'Anime'):
            $url = $this->movieDBServices->get_search_id_url($key, $type);
        endif;
        return $url;
    }

    public function format_api_data($type, $nom = '', $year = '')
    {
        $data = array();
        if($type == 'Film' || $type == 'Serie' || $type == 'Anime'):
            $data = $this->movieDBServices->format_array_search($this->movieKeyAPI, $nom, $year);
        endif;
        return $data;
    }

    public function search_by_name($type, $nom = '', $year = '')
    {
        $url = $this->get_api_url($type);
        $data = $this->format_api_data($type, $nom, $year);
        $liste = $this->callAPI('GET', $url, $data);
        $liste = json_decode($liste,true);
        return $liste;
    }

    public function search_by_key($type, $key)
    {
        $url = $this->get_search_id_url($key, $type);
        $data = $this->format_api_data($type);
        $liste = $this->callAPI('GET', $url, $data);
        $liste = json_decode($liste,true);
        return $liste;
    }

    public function create_liste_objet($type, $tab)
    {
        $array = array();
        $index = $tab['total_results'];
        for($i = 0; $i < $index; $i++ ):
            $element = $this->create_objet($type, $tab['results'][$i]);
            $array[] = $element;
        endfor;
        return $array;
    }

    public function create_objet($type, $tab, $element = null)
    {
        $data = null;
        $function = 'create_'.strtolower($type);
        if($type == 'Film' || $type == 'Serie' || $type == 'Anime'):
            $data = $this->movieDBServices->$function($tab, $element, $this);
        endif;
        return $data;
    }

    public function create_genre($element, $nom){
        $tag = $this->tagServices->findOne(array('libelle'=>$nom));
        if($tag == null){
            $tag = new Tag();
            $tag->setLibelle($nom);
        }
        $element->addTag();
    }

    public function get_empty_key(){
        $array = array();
        $array['Film'] = $this->movieDBServices->elementService->find('Film', array('apiKey' => null));
        $array['Serie'] = $this->movieDBServices->elementService->find('Serie', array('apiKey' => null));
        $array['Anime'] = $this->movieDBServices->elementService->find('Anime', array('apiKey' => null));

        return $array;
    }

    public function verif_key(){
        $type_array= array('Film','Serie','Anime');
        $array = array();

        foreach($type_array as $type):
            $liste = $this->movieDBServices->elementService->findAll(null, $type);

            foreach($liste as $element):
                $api_element = $this->search_by_key($type,$element->getApiKey());

                if(!isset($api_element['title'])):
                    $array[$type][] = $element;
                elseif($api_element['title'] != $element->getTitre()):
                    $array[$type][] = $element;
                endif;
            endforeach;
        endforeach;

        return $array;
    }

    function update_database($type = null){
        if($type == null):
            $array_type = array('Film','Serie','Anime');
        else:
            $array_type = array($type);
        endif;

        foreach ($array_type as $type) {
            $liste = $this->elementServices->findAll(0, $type, null);

            foreach($liste as $element):
                $api_element = $this->search_by_key($type,$element->getApiKey());
                if(!isset($api_element['status_code'])):
                    $element = $this->create_objet($type,$api_element,$element);
                    $this->elementServices->save($element);
                endif;
            endforeach;
        }
    }

    public function callAPI($method, $url, $data = false)
    {
        try {
            $curl = curl_init();

            switch ($method)
            {
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);

                    if ($data)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_PUT, 1);
                    break;
                default:
                    if ($data)
                        $url = sprintf("%s?%s", $url, http_build_query($data));
            }

            // Optional Authentication:
            /* curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
             curl_setopt($curl, CURLOPT_USERPWD, "username:password");*/

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);


            $result = curl_exec($curl);
            if (FALSE === $result)
                throw new \Exception(curl_error($curl), curl_errno($curl));
            curl_close($curl);

        } catch(\Exception $e) {
            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }dump($url);
        return $result;
    }
}