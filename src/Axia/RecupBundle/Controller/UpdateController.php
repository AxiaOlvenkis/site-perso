<?php

namespace Axia\RecupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UpdateController extends Controller
{
    public function indexAction()
    {
        $types = $this->get('type.services')->findAll();
        return $this->render('AxiaRecupBundle:Update:index.html.twig', array(
            'types' => $types
        ));
    }

    public function partsAction(Request $request)
    {
        $liste = array();
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $liste = $this->get('recup.services')->recup_update($str_type);


            return $this->render('AxiaRecupBundle:Update:parts.html.twig', array(
                'liste' => $liste,
                'type' => $str_type
            ));
        }

        return $this->indexAction();
    }
}