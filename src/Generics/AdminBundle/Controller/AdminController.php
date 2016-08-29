<?php

namespace Generics\AdminBundle\Controller;

use Generics\AdminBundle\Entity\Alerte;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function indexAction()
    {
        $listalerts = $this->get('alerte.services')->findAll();

        return $this->render('GenericsAdminBundle:Admin:admin.html.twig', array(
            'listAlerts' => $listalerts
        ));
    }

    public function deleteAlertAction(Alerte $alerte, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $this->get('alerte.services')->delete($alerte);
            $request->getSession()->getFlashBag()->add('info', "l'alerte a bien été supprimée.");
            return $this->redirect($this->generateUrl('core_admin'));
        }

        return $this->render('GenericsAdminBundle:Alerte:delete.html.twig', array(
            'alerte' => $alerte,
            'form'   => $form->createView()
        ));
    }
}
