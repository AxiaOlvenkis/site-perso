<?php

namespace Generics\AdminBundle\Services;

use Doctrine\ORM\EntityManager;
use Generics\AdminBundle\Entity\File;

class VerifServices
{
    public function verifCaptcha($reponse)
    {
        $secret = '6LcGKd0SAAAAAGx_e1Trl3XW4kAM-fOalkNSxTYN';
        $verify = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$reponse";

        return json_decode(file_get_contents($verify),true);
    }
}