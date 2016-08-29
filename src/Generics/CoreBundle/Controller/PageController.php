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
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->save($page);

            $request->getSession()->getFlashBag()->add('info', 'Page bien enregistrée.');
            return $this->redirect($this->generateUrl('admin_page'));
        }

        return $this->render('GenericsCoreBundle:Admin:page_add.html.twig', array(
                                    'form' => $form->createView()
                                ));
	}

	public function edit_pageAction(Page $page, Request $request)
	{
        $form = $this->createForm(PageType::class, $page);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->save($page);

            $request->getSession()->getFlashBag()->add('info', 'Page bien modifiée.');

            return $this->redirect($this->generateUrl('admin_page'));
        }

        return $this->render('GenericsCoreBundle:Admin:page_edit.html.twig', array(
                        'form'   => $form->createView(),
                        'page' => $page
                    ));
	}

	public function delete_pageAction(Page $page, Request $request)
	{
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('page.services')->delete($page);

            $request->getSession()->getFlashBag()->add('info', "la page a bien été supprimée.");

            return $this->redirect($this->generateUrl('admin_page'));
        }

        return $this->render('GenericsCoreBundle:Admin:page_delete.html.twig', array(
                                    'page' => $page,
                                    'form' => $form->createView()
                                ));
	}
}