<?php

namespace Generics\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $builder
                    ->add('titre') 
                    ->add('link')
                    ->add('texte');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'biblio_bundle_page';
    }
}
