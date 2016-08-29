<?php

namespace Axia\RecupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RecupController extends Controller
{
    public function indexAction()
    {
        $types = $this->get('type.services')->findAll();
        return $this->render('AxiaRecupBundle:Recup:index.html.twig', array(
            'types' => $types
        ));
    }

    public function partsAction(Request $request)
    {
        $liste = array();
        $erreur = '';
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $date = $this->get('config.services')->getMajBiblio(strtolower($str_type));
            $param_date = '';
            if($date != null):
                $param_date = '/ajout/'.$date;
            endif;

            try{
                $liste = json_decode(file_get_contents("http://www.lartmoukis.fr/api/elements/".$str_type.$param_date), true);
            }
            catch(\Exception $e){
                $erreur = 'Elements non trouvés';
            }

            return $this->render('AxiaRecupBundle:Recup:parts.html.twig', array(
                'liste' => $liste,
                'type' => $str_type,
                'erreur' => $erreur
            ));
        }

        return $this->indexAction();
    }

    public function recup_allAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $date = $this->get('config.services')->getMajBiblio(strtolower($type));
            $param_date = '';
            if($date != null):
                $param_date = '/ajout/'.$date;
            endif;
            $ob_type = $this->get('type.services')->findOne(array('libelle'=>$type));
            $recup = json_decode(
                file_get_contents("http://www.lartmoukis.fr/api/elements/".$type.$param_date),
                true);
            $user = $this->getUser();
            foreach ($recup as $tab):
                if(!$this->get('recup.services')->is_exist($tab['string_i_d'], $type)):
                    $this->get('recup.services')->create($ob_type, $tab, $user);
                endif;
            endforeach;

            $config = $this->get('config.services')->findOne(array('strId' => 'maj_biblio_'.strtolower($type)));
            $date_format = new \DateTime();
            $str_date = $date_format->format('d/m/Y');
            $config->setValue($str_date);
            $this->get('config.services')->save($config);

            $this->partsAction($request);
        }

        return $this->indexAction();
    }

    public function recup_singleAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            $type = $request->get('type');
            $ob_type = $this->get('type.services')->findOne(array('libelle'=>$type));
            $code = $request->get('stringID');
            if(!$this->get('recup.services')->is_exist($code, $type)):
                $recup = json_decode(
                    file_get_contents("http://www.lartmoukis.fr/api/element/".$type."/id/".$code),
                    true);
                $this->get('recup.services')->create($ob_type, $recup, $user);
            endif;

            $config = $this->get('config.services')->findOne(array('strId' => 'maj_biblio_'.strtolower($type)));
            $date_format = new \DateTime();
            $str_date = $date_format->format('d/m/Y');
            $config->setValue($str_date);
            $this->get('config.services')->save($config);

            $this->partsAction($request);
        }

        return $this->indexAction();
    }
}
