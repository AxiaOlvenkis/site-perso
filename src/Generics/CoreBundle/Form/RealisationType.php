<?php

namespace Generics\CoreBundle\Form;

use Generics\AdminBundle\Form\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RealisationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $builder
                    ->add('titre') 
                    ->add('description')
                    ->add('lien')
                    ->add('image', FileType::class)
                    ->add('save', SubmitType::class);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_bundle_real';
    }
}
