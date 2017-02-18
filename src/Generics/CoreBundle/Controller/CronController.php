<?php

namespace Generics\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CronController extends Controller
{
    public function updateAction()
    {
        $types = $this->get('type.services')->findAll();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserByUsername('AxiaOlvenkis');
        //$user = $this->getUser();

        foreach ($types as $type):
            $this->get('update.services')->full_update($type->getLibelle(),$user);
        endforeach;

        return $this->redirect($this->generateUrl('recup_update'));
    }
}