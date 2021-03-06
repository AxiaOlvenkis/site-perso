<?php
/**
 * Created by PhpStorm.
 * User: hb
 * Date: 25/04/2016
 * Time: 10:03
 */

namespace Axia\RecupBundle\Services;

use Axia\AutoUpdateBundle\Services\LartMoukisDBServices;
use Axia\BiblioBundle\Entity\Collection;
use Axia\BiblioBundle\Entity\Editeur;
use Axia\BiblioBundle\Entity\Element;
use Axia\BiblioBundle\Entity\Num_Collection_Film;
use Axia\BiblioBundle\Entity\Num_Collection_Livre;
use Axia\BiblioBundle\Entity\Personne;
use Axia\BiblioBundle\Entity\Tag;
use Axia\BiblioBundle\Entity\Type;
use Axia\BiblioBundle\Services\EditeurServices;
use Axia\BiblioBundle\Services\ElementServices;
use Axia\BiblioBundle\Services\SaisonServices;
use Axia\BiblioBundle\Services\TypeServices;
use Axia\UserBiblioBundle\Entity\Biblio;
use Axia\UserBiblioBundle\Services\BiblioServices;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Generics\UserBundle\Entity\User;

class UpdateServices
{
    /**
     * @var EntityManager em
     */
    private $em;

    private $elementService;
    private $biblioService;
    private $tagService;
    private $personneService;
    private $editeurService;
    private $collectionService;
    private $lmdbServices;
    private $saisonService;

    /**
     * RecupServices constructor.
     * @param $entityManager
     * @param ElementServices $elementService
     * @param BiblioServices $biblioService
     * @param $tagService
     * @param $personneService
     * @param EditeurServices $editeurService
     * @param $collectionService
     * @param TypeServices $typeService
     * @param LartMoukisDBServices $lmdbServices
     * @param SaisonServices $saisonService
     */
    public function __construct($entityManager, $elementService, $biblioService, $tagService,
                                $personneService, $editeurService, $collectionService, $typeService, $lmdbServices, $saisonService)
    {
        $this->em = $entityManager;
        $this->elementService = $elementService;
        $this->biblioService = $biblioService;
        $this->tagService = $tagService;
        $this->personneService = $personneService;
        $this->editeurService = $editeurService;
        $this->collectionService = $collectionService;
        $this->typeService = $typeService;
        $this->lmdbServices = $lmdbServices;
        $this->saisonService = $saisonService;
    }

    public function recup_update($type)
    {
        $liste = array();
        $liste_element = $this->elementService->findFilter($type);

        foreach ($liste_element as $element)
        {
            $api_element = '';
            try{
                $api_element = $this->lmdbServices->search('element','','','',$type, $element->getStringID());
            }
            catch(\Exception $e){
                $erreur = 'Elements non trouvés';
            }

            if(isset($api_element['date_edit']))
            {
                $date = new \DateTime($api_element['date_edit']);
                if($element->getDateEdit() <= $date)
                {
                    $liste[] = $element;
                }
            }

        }
        return $liste;
    }

    /**
     * @param Element $element
     */
    public function update_solo($element, $user, $full = false)
    {
        $api_element = '';
        $type = $element->getType();
        try{
            $api_element = $this->lmdbServices->search('element','','','',$type, $element->getStringID());
        }
        catch(\Exception $e){
            $erreur = 'Elements non trouvés';
        }
        
        if(isset($api_element['error'])):
            return;
        endif;
        
        if($full == false) {
            if (isset($api_element['date_edit'])) {
                $date = new \DateTime($api_element['date_edit']);
                if ($element->getDateEdit() <= $date) {
                    $this->save_element($element, $api_element, $user);
                }
            }
        }
        else {
            $this->save_element($element, $api_element, $user);
        }
    }

    public function update_all($type, $user)
    {
        $liste_element = $this->elementService->findFilter($type);
        foreach ($liste_element as $element)
        {
            $this->update_solo($element, $user);
        }
    }

    public function full_update($type, $user)
    {
        $liste_element = $this->elementService->findFilter($type);
        foreach ($liste_element as $element)
        {
            $this->update_solo($element, $user, true);
        }
    }

    /**
     * @param Element $element
     * @param array $recup
     */
    public function save_element($element, $recup, $user)
    {
        $lib_type = $element->getType();
        $type = $this->typeService->findOne(array('libelle' => $lib_type));
        if(isset($recup['titre'])):
            $element->setTitre($recup['titre']);
        else:
            $element->setTitre('');
        endif;
        if(isset($recup['titre_vf'])):
            $element->setTitreVF($recup['titre_vf']);
        else:
            $element->setTitreVF($recup['titre']);
        endif;
        if(isset($recup['fiche'])):
            $element->setFiche($recup['fiche']);
        else:
            $element->setFiche('');
        endif;
        if(isset($recup['date_parution'])):
            $element->setDateParution(new \DateTime($recup['date_parution']));
        else:
            $element->setDateParution(null);
        endif;

        if($type->getLibelle() == 'Anime' || $type->getLibelle() == 'Serie')
        {
            $element->setNbEpisode($recup['nb_episode']);
            $element->setFini($recup['fini']);
        }
        elseif($type->getLibelle() == 'Manga' || $type->getLibelle() == 'BD' || $type->getLibelle() == 'Comics')
        {
            $element->setNbTomeVO($recup['nb_tome_v_o']);
            $element->setNbTomeVF($recup['nb_tome_v_f']);
            $element->setFini($recup['fini']);
        }
        elseif($type->getLibelle() == 'Livre')
        {
            if(isset($recup['isbn'])):
                if($recup['isbn'] == ''):
                    $element->setIsbn($recup['string_i_d']);
                else:
                    $element->setIsbn($recup['isbn']);
                endif;
            else:
                $element->setIsbn($recup['string_i_d']);
            endif;
        }

        if(isset($recup['tags']))
        {
            foreach ($recup['tags'] as $tab_tag)
            {
                $element = $this->save_tag($element, $tab_tag['libelle']);
            }
        }
        if(isset($recup['auteurs']))
        {
            foreach ($recup['auteurs'] as $tab_aut)
            {
                $element = $this->save_auteur($element, $tab_aut['nom'], $tab_aut['prenom'], $type);
            }
        }
        if(isset($recup['editeurs']))
        {
            foreach ($recup['editeurs'] as $tab_ed)
            {
                $element = $this->save_editeur($element, $tab_ed['nom'], $type);
            }
        }
        /*if(isset($recup['collection']))
        {
            foreach ($recup['collection'] as $tab_col)
            {
                $element = $this->save_collection($element, $tab_col['nom'], $recup['collection']['numero'], $type);
            }
        }*/

        if(isset($recup['saisons'])){
            foreach($recup['saisons'] as $season){
                $element = $this->saveSaison($element, $type->getLibelle(), $season, $user);
            }
        }
        $this->elementService->save($element);

        if($type->getLibelle() == 'Anime' || $type->getLibelle() == 'Serie'):
            foreach($element->getSaisons() as $saison):
                $search = $this->biblioService->findOneByTypeAndId($type->getLibelle(), $saison, $user);
                if($search == null):
                    $biblio = new Biblio();
                    $biblio->setUser($user);
                    $func_element = "set".$type->getLibelle();
                    $biblio->$func_element($saison);
                    $biblio->setType($type->getLibelle());
                    $biblio->setValide(1);
                    //$biblio->setDernierVu($recup['nb_episode']);
                    $this->biblioService->save($biblio);
                else:
                    //$search->setDernierVu($recup['nb_episode']);
                    $this->biblioService->save($search);
                endif;
            endforeach;
        endif;
    }

    private function saveSaison($element, $type, $array, $user){
        $saison = $this->saisonService->findOne($type, array(
            'numero' => $array['numero'],
            strtolower($type) => $element
        ));

        if(!is_object($saison))
        {
            $constructor = "Axia\\BiblioBundle\\Entity\\".'Saison'.$type;
            $setter = 'set'.$type;
            $saison = new $constructor();
            $saison->$setter($element);
            $saison->setNumero($array['numero']);
        }

        $saison->setNbEpisodes($array['nb_episodes']);
        $saison->setNbEpisodesSortis($array['nb_episodes_sortis']);
        $saison->setFini($array['fini']);
        $element->addSaison($saison);
        return $element;
    }

    private function save_tag($element, $libelle)
    {
        $tag = $this->tagService->findOne(array('libelle' => $libelle));

        // si c'est un objet, le tag existe deja, pas besoin de le creer
        if(!is_object($tag))
        {
            $tag = new Tag();
            $tag->setLibelle($libelle);
        }
        if(!$element->getTags()->contains($tag))
        {
            $element->addTag($tag);
        }

        return $element;
    }

    private function save_auteur($element, $nom, $prenom, $type)
    {
        $auteur = $this->personneService->findOne(array('nom' => $nom, 'prenom' => $prenom));

        // si c'est un objet, la personne existe deja, pas besoin de le creer
        if(!is_object($auteur))
        {
            $auteur = new Personne();
            $auteur->setNom($nom);
            $auteur->setPrenom($prenom);
            $auteur->addType($type);
        }
        elseif(!$auteur->getTypes()->contains($type))
        {
            $auteur->addType($type);
        }
        if(!$element->getAuteurs()->contains($auteur))
        {
            $element->addAuteur($auteur);
        }

        return $element;
    }

    private function save_editeur($element, $nom, $type)
    {
        $editeur = $this->editeurService->findOne(array('nom' => $nom));

        // si c'est un objet, l'editeur existe deja, pas besoin de le creer
        if(!is_object($editeur))
        {
            $editeur = new Editeur();
            $editeur->setNom($nom);
            $editeur->addType($type);
        }
        elseif(!$editeur->getTypes()->contains($type))
        {
            var_dump('poney');
            $editeur->addType($type);
        }
        if(!$element->getEditeurs()->contains($editeur))
        {
            $element->addEditeur($editeur);
        }

        return $element;
    }

    private function save_collection($element, $nom, $numero, $type)
    {
        $collection = $this->collectionService->findOne(array('nom' => $nom, 'type' => $type));

        // si c'est un objet, la collection existe deja, pas besoin de le creer
        if(!is_object($collection))
        {
            $collection = new Collection();
            $collection->setNom($nom);
            $collection->setType($type);
        }
        if($type == 'Livre'):
            $num_collection = new Num_Collection_Livre();
            $num_collection->setLivre($element);
        else:
            $num_collection = new Num_Collection_Film();
            $num_collection->setFilm($element);
        endif;
        $num_collection->setCollection($collection);
        $num_collection->setNumero($numero);
        $element->setCollection($num_collection);

        return $element;
    }

    public function is_exist($id, $type)
    {
        $objet = $this->elementService->findOne($type, array('stringID' => $id));
        if(!is_object($objet))
        {
            return false;
        }

        return true;
    }
}