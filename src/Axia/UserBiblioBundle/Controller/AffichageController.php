<?php

namespace Axia\UserBiblioBundle\Controller;

use Axia\UserBiblioBundle\Form\BiblioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AffichageController extends Controller
{
    public function indexAction()
    {
        $types = $this->get('type.services')->findAll();
        return $this->render('AxiaUserBiblioBundle:Biblio:index.html.twig', array(
            'types' => $types
        ));
    }

    public function accueilAction()
    {
        $booksOfTheMonth = $this->get('biblio.services')->repoPerso('Livre', 'SortieduMois');
        $animes = $this->get('biblio.services')->repoPerso('Anime', 'en_cours');
        $series = $this->get('biblio.services')->repoPerso('Serie', 'en_cours');
        $films = $this->get('biblio.services')->repoPerso('Film', 'a_l_affiche');
        $booksToRead = $this->get('biblio.services')->repoPerso('Livre', 'a_voir');
        return $this->render('AxiaUserBiblioBundle:Biblio:accueil.html.twig', array(
            'Livres' => $booksOfTheMonth,
            'Animes' => $animes,
            'Series' => $series,
            'Films' => $films,
            'ToReads' => $booksToRead
        ));
    }

    public function courseAction()
    {
        return $this->render('AxiaUserBiblioBundle:Biblio:course.html.twig');
    }

    public function partsAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $user = $this->get('user.services')->findOneByPseudo('AxiaOlvenkis');
            $liste = $this->get('biblio.services')->find(array(
                'type' => $str_type,
                'valide' => 1,
                'user' => $user
            ));

            return $this->render('AxiaUserBiblioBundle:Parts:parts_'.$str_type.'.html.twig', array(
                'liste' => $liste,
                'type' => $str_type
            ));
        }

        return $this->editionAction();
    }

    public function listeAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $filtre = $request->get('filtre');
            $user = $this->get('user.services')->findOneByPseudo('AxiaOlvenkis');

            if($filtre == 'all'):
                $array = array(
                    'type' => $str_type,
                    'valide' => 1,
                    'user' => $user
                );
                $liste = $this->get('biblio.services')->find($array);
            elseif($filtre == 'collection'):
                $array = array(
                    'type' => $str_type,
                    'valide' => 1,
                    'user' => $user,
                    'possede' => 1
                );
                $liste = $this->get('biblio.services')->find($array);
            elseif($filtre == 'recherche'):
                $search = $request->get('search');
                $liste = $this->get('biblio.services')->repoPerso($str_type, $filtre, $search);
            else:
                $liste = $this->get('biblio.services')->repoPerso($str_type, $filtre);
            endif;

            return $this->render('AxiaUserBiblioBundle:Liste:liste_'.$str_type.'.html.twig', array(
                'liste' => $liste,
                'type' => $str_type
            ));
        }

        return $this->indexAction();
    }
}
