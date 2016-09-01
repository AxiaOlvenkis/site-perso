<?php

namespace Axia\UserBiblioBundle\Controller;

use Axia\UserBiblioBundle\Form\BiblioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EditionController extends Controller
{
    public function editionAction()
    {
        $types = $this->get('type.services')->findAll();
        return $this->render('AxiaUserBiblioBundle:AdminEdition:index.html.twig', array(
            'types' => $types
        ));
    }

    public function partsAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $str_type = $request->get('type');
            $user = $this->getUser();
            $liste = $this->get('biblio.services')->find(array(
                            'type' => $str_type,
                            'valide' => 0,
                            'user' => $user
            ));

            return $this->render('AxiaUserBiblioBundle:AdminEdition:parts.html.twig', array(
                'liste' => $liste,
                'type' => $str_type
            ));
        }

        return $this->editionAction();
    }

    public function updateAction(Request $request, $link, $id)
    {
        $element = $this->get('biblio.services')->findOneById($id);
        $form = $this->createForm(BiblioType::class, $element);

        if ($form->handleRequest($request)->isValid()) {
            $element->setValide(1);
            $this->get('biblio.services')->save($element);

            $request->getSession()->getFlashBag()->add('info', 'Element bien modifié.');

            if($link == 'admin'):
                return $this->redirect($this->generateUrl('biblio_edition'));
            else:
                $type = $element->getType();
                return $this->redirect($this->generateUrl('biblio_homepage'/*, ['_fragment' => $type]*/));
            endif;
        }

        return $this->render('AxiaUserBiblioBundle:AdminEdition:form.html.twig', array(
            'form' => $form->createView(),
            'element' => $element
        ));
    }

    public function deleteAction(Request $request, $type, $id)
    {
        $element = $this->get('biblio.services')->findOneById($id);
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('biblio.services')->delete($element);

            $request->getSession()->getFlashBag()->add('info', 'L\'element a bien été supprimée.');

            return $this->redirect($this->generateUrl('admin_homepage'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('AxiaBiblioBundle:Element:delete.html.twig', array(
            'element' => $element,
            'form'   => $form->createView()
        ));
    }

    public function editNbAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            $action = $request->get('action');

            $element = $this->get('biblio.services')->findOneById($id);
            $nb = 0;

            if($action == 'plus'):
                $nb = $element->getDernierVu();
                $nb = $nb + 1;
                $element->setDernierVu($nb);
            elseif($action == 'moins'):
                $nb = $element->getDernierVu();
                $nb = $nb - 1;
                $element->setDernierVu($nb);
            elseif($action == 'note-moins'):
                $nb = $element->getNote();
                $nb = $nb - 1;
                $element->setNote($nb);
            elseif($action == 'note-plus'):
                $nb = $element->getNote();
                $nb = $nb + 1;
                $element->setNote($nb);
            endif;
            $this->get('biblio.services')->save($element);

            if($action == 'plus' || $action == 'moins')
            {
                return new Response($nb);
            }
            elseif($action == 'note-plus' || $action == 'note-moins')
            {
                return $this->render('AxiaUserBiblioBundle:Fragments:frag_num_note.html.twig', array(
                    'biblio' => $element
                ));
            }
        }

        return $this->editionAction();
    }

    public function editBoolAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $id = $request->get('id');
            $action = $request->get('action');

            $element = $this->get('biblio.services')->findOneById($id);
            $getter = "get".$action;
            $setter = "set".$action;
            $bool = $element->$getter();
            $bool = !$bool;

            $element->$setter($bool);
            $this->get('biblio.services')->save($element);

            if($element->$getter()):
                $reponse = "<i class=\"fa fa-check-square-o\"></i>";
            else:
                $reponse = "<i class=\"fa fa-square-o\"></i>";
            endif;

            return new Response($reponse);
        }

        return $this->editionAction();
    }
}
