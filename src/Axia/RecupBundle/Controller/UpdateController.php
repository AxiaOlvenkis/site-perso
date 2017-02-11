<?php

namespace Axia\RecupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateController extends Controller
{
    public function indexAction()
    {
        $types = $this->get('type.services')->findAll();
        return $this->render('AxiaRecupBundle:Update:index.html.twig', array(
            'types' => $types
        ));
    }

    public function view_updateAction(){
        $types = $this->get('type.services')->findAll();
        $liste = array();

        foreach ($types as $type):
            $items = $this->get('update.services')->recup_update($type->getLibelle());
            $liste[$type->getLibelle()] = $items;
        endforeach;
        return $this->render('AxiaRecupBundle:Update:liste.html.twig', array(
            'liste' => $liste
        ));
    }

    public function recup_allAction()
    {
        $types = $this->get('type.services')->findAll();
        $user = $this->getUser();

        foreach ($types as $type):
            $this->get('update.services')->full_update($type->getLibelle(),$user);
        endforeach;

        return $this->redirect($this->generateUrl('recup_update'));
    }

    public function recup_singleAction($type, $id)
    {
        $element = $this->get('element.services')->findOne($type, array(
            'stringID' => $id
        ));
        $user = $this->getUser();
        $this->get('update.services')->update_solo($element,$user);
        return $this->redirect($this->generateUrl('recup_update'));
    }

    /**
     * Tache Cron
     * @return Response
     */
    public function autoAction()
    {
        $types_liste = $this->get('type.services')->findAll();

        foreach($types_liste as $type)
        {
            $this->get('update.services')->update_all($type->getLibelle());
        }

        return new Response();
    }
}