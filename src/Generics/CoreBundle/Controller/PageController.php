<?php

namespace Generics\CoreBundle\Controller;

use Generics\CoreBundle\Entity\Page;
use Generics\CoreBundle\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
	public function view_pageAction()
	{
        $listpages = $this->get('page.services')->findAll();

        return $this->render('GenericsCoreBundle:Admin:page_view.html.twig', array(
	      'listPages' => $listpages
	    ));
	}

	public function add_pageAction(Request $request)
	{
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->save($page);

            $session->getFlashBag()->add('info', 'Page bien enregistrée.');

            if($session->get('referer') == '' || $session->get('referer') == null):
                return $this->redirect($this->generateUrl('admin_page'));
            else:
                return $this->redirect($session->get('referer'));
            endif;
        }
        else{
            $session->set('referer',$referer);
            return $this->render('GenericsCoreBundle:Admin:page_add.html.twig', array(
                'form' => $form->createView()
            ));
        }
	}

	public function edit_pageAction(Page $page, Request $request)
	{
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $form = $this->createForm(PageType::class, $page);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->save($page);

            $session->getFlashBag()->add('info', 'Page bien modifiée.');

            if($session->get('referer') == '' || $session->get('referer') == null):
                return $this->redirect($this->generateUrl('admin_page'));
            else:
                return $this->redirect($session->get('referer'));
            endif;
        }
        else{
            $session->set('referer',$referer);

            return $this->render('GenericsCoreBundle:Admin:page_edit.html.twig', array(
                'form'   => $form->createView(),
                'page' => $page
            ));
        }
	}

	public function delete_pageAction(Page $page, Request $request)
	{
        $session = $request->getSession();
        $referer = $request->headers->get('referer');
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->delete($page);

            $session->getFlashBag()->add('info', "la page a bien été supprimée.");

            if($session->get('referer') == '' || $session->get('referer') == null):
                return $this->redirect($this->generateUrl('admin_page'));
            else:
                return $this->redirect($session->get('referer'));
            endif;
        }
        else{
            $session->set('referer',$referer);

            return $this->render('GenericsCoreBundle:Admin:page_delete.html.twig', array(
                'page' => $page,
                'form' => $form->createView()
            ));
        }
	}
}