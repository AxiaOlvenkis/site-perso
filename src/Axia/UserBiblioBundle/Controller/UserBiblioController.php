<?php

namespace Axia\UserBiblioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserBiblioController extends Controller
{
    public function indexAction()
    {
        return $this->render('AxiaUserBiblioBundle:Biblio:index.html.twig');
    }
}
