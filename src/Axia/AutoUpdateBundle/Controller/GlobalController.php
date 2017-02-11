<?php

namespace Axia\AutoUpdateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GlobalController extends Controller
{
    public function indexAction()
    {
        return $this->render('AxiaAutoUpdateBundle:Global:index.html.twig');
    }

    public function empty_keyAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $choix = $request->get('choix');
            $liste = array();
            if($choix == "empty_key"):
                $liste = $this->get('general.services')->get_empty_key();
            elseif($choix == 'not_good'):
                $liste = $this->get('general.services')->verif_key();
            elseif($choix == 'update'):
                $liste['Film'] = $this->get('element.services')->findAll(null,'Film',null);
                $liste['Serie'] = $this->get('element.services')->findAll(null,'Serie',null);
                $liste['Anime'] = $this->get('element.services')->findAll(null,'Anime',null);
            endif;

            return $this->render('AxiaAutoUpdateBundle:Global:content_element.html.twig', array(
                'liste' => $liste,
                'choix' => $choix
            ));
        }

        return $this->indexAction();
    }

    public function empty_key_singleAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $id = $request->get('id');
            $element = $this->get('element.services')->findElement($id,$type);
            if($element->getDateParution() != null){
                $date = $element->getDateParution()->format('Y');
            }
            else{
                $date = '';
            }
            $api_liste = $this->get('general.services')->search_by_name($type, $element->getTitre(), $date);
            $liste = $api_liste["results"];
            $fiche_url = $this->get('general.services')->get_fiche_url($type);

            return $this->render('AxiaAutoUpdateBundle:Modal:modal_empty_key_'.$type.'.html.twig', array(
                'liste' => $liste,
                'element' => $element,
                'fiche_url' => $fiche_url
            ));
        }

        return $this->indexAction();
    }

    public function maj_keyAction(Request $request, $type){
        $error = 0;
        $success = 0;
        $limit = 100;
        $offset = 0;
        $liste = $this->get('element.services')->findAll($offset, $type, $limit);

        while(!empty($liste)){
            $offset = $limit;
            $limit = $limit + 100;

            foreach($liste as $element){
                if($element->getDateParution() != null){
                    $date = $element->getDateParution()->format('Y');
                }
                else{
                    $date = '';
                }
                $api_liste = $this->get('general.services')->search_by_name($type, $element->getTitre(), $date);
                $results = $api_liste["results"];
                if(count($results) > 1 || count($results) == 0):
                    $error++;
                else:
                    $element = $this->get('general.services')->create_objet($type, $results[0], $element);
                    $this->get('element.services')->save($element);
                    $success++;
                endif;
            }

            $liste = $this->get('element.services')->findAll($offset, $type, $limit);
        }

        $request->getSession()->getFlashBag()->add('error', $error.' element(s) n\'ont pu être mis à jour.');
        $request->getSession()->getFlashBag()->add('info', $success.' élément ont bien été mis à jour.');

        return $this->render('AxiaAutoUpdateBundle:Global:index.html.twig');
    }

    public function add_keyAction(Request $request,$type, $id, $key){
        if($request->isMethod('POST')):
            $key = $request->request->get('key');
        endif;
        $element = $this->get('element.services')->findElement($id,$type);
        $element->setApiKey($key);
        $this->get('element.services')->save($element);

        return $this->redirect($this->generateUrl('auto_update_homepage'));
    }

    public function update_singleAction(Request $request){
        $type = $request->get('type');
        $id = $request->get('id');
        $element = $this->get('element.services')->findElement($id,$type);

        $api_element = $this->get('general.services')->search_by_key($type,$element->getApiKey());
        if(isset($api_element['status_code']) && $api_element['status_code'] == 34):
            return new Response();
        endif;

        $element = $this->get('general.services')->create_objet($type,$api_element,$element);
        $this->get('element.services')->save($element);

        return new Response(json_encode($element->getTitre()));
    }

    public function update_allAction($type){
        $this->get('general.services')->update_database($type);
        return $this->redirectToRoute('auto_update_homepage');
    }

    public function add_afficheAction(Request $request){
        $types = array('Film','Serie','Anime');

        return $this->render('AxiaAutoUpdateBundle:Global:add_element.html.twig', array(
            'types' => $types
        ));
    }

    public function add_searchAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $search = $request->get('search');
            $api_liste = $this->get('general.services')->search_by_name($type, $search);
            $liste = $api_liste["results"];
            $fiche_url = $this->get('general.services')->get_fiche_url($type);

            foreach($liste as $key => $element){
                $element = $this->get('element.services')->find($type, array('apiKey'=>$element['id']));
                if($element != null):
                    unset($liste[$key]);
                endif;
            }

            return $this->render('AxiaAutoUpdateBundle:Search:add_search_'.$type.'.html.twig', array(
                'liste' => $liste,
                'fiche_url' => $fiche_url,
                'type' => $type
            ));
        }

        return $this->indexAction();
    }

    public function addAction($type, $key){
        $api_element = $this->get('general.services')->search_by_key($type,$key);

        $element = $this->get('general.services')->create_objet($type,$api_element);
        $this->get('element.services')->save($element);

        return $this->redirectToRoute('auto_update_homepage');
    }
}
