<?php

namespace Axia\RecupBundle\Controller;

use Axia\RecupBundle\Entity\TempoEntity;
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
    public function recupAction(Request $request)
    {
        $types = $this->get('type.services')->findAll();//$request->get('type');
        $total = 0;
        $recup = 0;
        $deja = 0;
        $em = $this->getDoctrine()->getManager();
        foreach ($types as $type) {
            $lst_recup = json_decode(
                file_get_contents("http://www.lartmoukis.fr/api/elements/".$type->getLibelle()),
                true);
            foreach($lst_recup as $json_ob):
                $total++;
                if(!$this->get('recup.services')->is_exist($json_ob['string_i_d'], $type->getLibelle()) && !$this->get('recup.services')->in_tempo_table($json_ob['string_i_d'], $type->getLibelle())):
                    $ob_recup = new TempoEntity();
                    $ob_recup->setStringID($json_ob['string_i_d']);
                    $ob_recup->setType($json_ob['type']);
                    $ob_recup->setRefuse(0);

                    $em->persist($ob_recup);
                    $em->flush();

                    $recup++;
                    $liste[] = $ob_recup;
                else:
                    $deja++;
                endif;
            endforeach;
        }
        $liste = array(
            'total' => $total,
            'recup' => $recup,
            'deja' => $deja
        );

        return $this->render('AxiaRecupBundle:Recup:recap_recup.html.twig', array(
            'liste' => $liste
        ));
    }

    public function liste_to_addAction(){
        $em = $this->getDoctrine()->getManager();
        $liste_tempo = $em->getRepository('AxiaRecupBundle:TempoEntity')->findBy(array('refuse' => 0));
        $liste = array();
        $erreur = '';

        foreach ($liste_tempo as $item):
            try{
                $url = "http://www.lartmoukis.fr/api/element/".$item->getType().'/id/'.$item->getStringID();
                $ob_element = json_decode(file_get_contents($url), true);

                $liste[] = $ob_element;
            }
            catch(\Exception $e){
                $erreur = 'Elements non trouvés';
            }
        endforeach;

        return $this->render('AxiaRecupBundle:Recup:liste.html.twig', array(
            'liste' => $liste,
            'erreur' => $erreur
        ));
    }

    public function liste_refusAction(){
        $em = $this->getDoctrine()->getManager();
        $liste_tempo = $em->getRepository('AxiaRecupBundle:TempoEntity')->findBy(array('refuse' => 1));
        $liste = array();
        $erreur = '';

        foreach ($liste_tempo as $item):
            try{
                $url = "http://www.lartmoukis.fr/api/element/".$item->getType().'/id/'.$item->getStringID();
                $ob_element = json_decode(file_get_contents($url), true);

                $liste[] = $ob_element;
            }
            catch(\Exception $e){
                $erreur = 'Elements non trouvés';
            }
        endforeach;

        return $this->render('AxiaRecupBundle:Recup:liste_refuse.html.twig', array(
            'liste' => $liste,
            'erreur' => $erreur
        ));
    }

    public function refuseAction($id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AxiaRecupBundle:TempoEntity')->findOneBy(array('stringID' => $id));

        $item->setRefuse(1);
        $em->flush();
        return $this->redirect($this->generateUrl('liste_api'));
    }

    public function reverse_refuseAction($id){
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AxiaRecupBundle:TempoEntity')->findOneBy(array('stringID' => $id));

        $item->setRefuse(0);
        $em->flush();
        return $this->redirect($this->generateUrl('liste_refuse_api'));
    }

    public function add_itemAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $item = $em->getRepository('AxiaRecupBundle:TempoEntity')->findOneBy(array('stringID' => $id));
        $ob_type = $this->get('type.services')->findOne(array('libelle'=>$item->getType()));

        $recup = json_decode(
            file_get_contents("http://www.lartmoukis.fr/api/element/".$item->getType()."/id/".$id),
            true);
        $this->get('recup.services')->create($ob_type, $recup, $user);

        $em->remove($item);
        $em->flush();

        return $this->redirect($this->generateUrl('liste_api'));
    }

    public function add_all_itemAction(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $liste_item = $em->getRepository('AxiaRecupBundle:TempoEntity')->findBy(array('refuse' => 0));

        foreach ($liste_item as $item):
            $ob_type = $this->get('type.services')->findOne(array('libelle'=>$item->getType()));

            $recup = json_decode(
                file_get_contents("http://www.lartmoukis.fr/api/element/".$item->getType()."/id/".$item->getStringID()),
                true);
            $this->get('recup.services')->create($ob_type, $recup, $user);

            $em->remove($item);
            $em->flush();
        endforeach;

        return $this->redirect($this->generateUrl('liste_api'));
    }
}
