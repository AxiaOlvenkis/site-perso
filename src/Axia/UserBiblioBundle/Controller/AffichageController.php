<?php

namespace Axia\UserBiblioBundle\Controller;

use Axia\UserBiblioBundle\Form\BiblioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $animes = $this->get('biblio.services')->repoPerso('Anime', 'en_cours_a_voir');
        $series = $this->get('biblio.services')->repoPerso('Serie', 'en_cours_a_voir');
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

        return $this->indexAction();
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

    public function courseAction()
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
            ->add('livre', CheckboxType::class, array('required' => false))
            ->add('jeu', CheckboxType::class, array('required' => false))
            ->add('manga', CheckboxType::class, array('required' => false))
            ->add('bd', CheckboxType::class, array('required' => false))
            ->add('comic', CheckboxType::class, array('required' => false))
            ->add('save', SubmitType::class, array('label' => 'Afficher la liste'))
            ->getForm();
        return $this->render('AxiaUserBiblioBundle:Biblio:course.html.twig', array(
            'form' => $form->createView(),
            'listLivres' => "",
            'listJeux' => "",
            'listMangas' => "",
            'listBD' => "",
            'listComics' => ""));
    }

    public function listeCourseAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $listLivres = '';
            $listJeux = '';
            $listMangas = '';
            $listBD = '';
            $listComics = '';

            if($request->get('livre')=='true')
            {
                $listLivres = $this->get('biblio.services')->repoPerso('Livre', 'souhait');
            }
            if($request->get('jeu')=='true')
            {
                $listJeux = $this->get('biblio.services')->repoPerso('Jeu', 'souhait');
            }
            if($request->get('manga')=='true')
            {
                $listMangas = $this->get('biblio.services')->repoPerso('Manga', 'liste_course');
            }
            if($request->get('bd')=='true')
            {
                $listBD = $this->get('biblio.services')->repoPerso('BD', 'liste_course');
            }
            if($request->get('comic')=='true')
            {
                $listComics = $this->get('biblio.services')->repoPerso('Comics', 'liste_course');
            }

            echo count($listBD);

            return $this->render('AxiaUserBiblioBundle:Biblio:lst_course.html.twig', array(
                'listLivres' => $listLivres,
                'listJeux' => $listJeux,
                'listMangas' => $listMangas,
                'listBD' => $listBD,
                'listComics' => $listComics
            ));
        }
        else
        {
            return $this->formCourseAction();
        }

    }
}
