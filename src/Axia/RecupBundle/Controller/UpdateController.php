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

    public function partsAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $liste = $this->get('update.services')->recup_update($str_type);

            return $this->render('AxiaRecupBundle:Update:parts.html.twig', array(
                'liste' => $liste,
                'type' => $str_type
            ));
        }

        return $this->indexAction();
    }

    public function recup_allAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $this->get('update.services')->update_all($type);
        }

        return $this->indexAction();
    }

    public function recup_singleAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $type = $request->get('type');
            $code = $request->get('stringID');
            $element = $this->get('element.services')->findOne($type, array(
                'stringID' => $code
            ));
            $this->get('update.services')->update_solo($element);

            return new Response();
        }

        return $this->indexAction();
    }
}