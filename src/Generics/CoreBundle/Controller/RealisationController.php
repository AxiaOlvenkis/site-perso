<?php

namespace Generics\CoreBundle\Controller;

use Generics\CoreBundle\Entity\Realisation;
use Generics\CoreBundle\Form\RealisationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RealisationController extends Controller
{
	public function view_realAction()
	{
        $listRea = $this->get('real.services')->findAll();
        return $this->render('GenericsCoreBundle:Realisation:real_view.html.twig', array(
            'listRea' => $listRea
        ));
	}

	public function realisationAction()
	{
        $listRea = $this->get('real.services')->findAll();
        return $this->render('GenericsCoreBundle:Realisation:realisation.html.twig', array(
            'listReal' => $listRea
        ));
	}

	public function add_realAction(Request $request)
	{
        $real = new Realisation();
        $form = $this->createForm(RealisationType::class, $real);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('real.services')->save($real);

            $request->getSession()->getFlashBag()->add('info', 'Réalisation bien enregistrée.');
            return $this->redirect($this->generateUrl('admin_real'));
        }

        return $this->render('GenericsCoreBundle:Realisation:real_add.html.twig', array(
            'form' => $form->createView()
        ));
	}

	public function edit_realAction(Realisation $real, Request $request)
	{
        $form = $this->createForm(RealisationType::class, $real);

        if ($form->handleRequest($request)->isValid()) {
            $this->get('real.services')->save($real);
            $request->getSession()->getFlashBag()->add('info', 'Réalisation bien modifiée.');
            return $this->redirect($this->generateUrl('admin_real'));
        }

        return $this->render('GenericsCoreBundle:Realisation:real_edit.html.twig', array(
            'form'   => $form->createView(),
            'real' => $real
        ));
	}

	public function delete_realAction(Realisation $real, Request $request)
	{		
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('real.services')->delete($real);
            $request->getSession()->getFlashBag()->add('info', "la realisation a bien été supprimée.");
        return $this->redirect($this->generateUrl('admin_real'));
        }

        return $this->render('GenericsCoreBundle:Realisation:real_delete.html.twig', array(
            'real' => $real,
            'form'   => $form->createView()
        ));
	}
}