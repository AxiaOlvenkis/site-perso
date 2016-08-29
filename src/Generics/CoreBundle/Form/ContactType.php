<?php

namespace Generics\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $builder
                    ->add('nom') 
                    ->add('sujet')
                    ->add('texte')
                    ->add('mail', EmailType::class, array('required' => false));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_bundle_contact';
    }
}
