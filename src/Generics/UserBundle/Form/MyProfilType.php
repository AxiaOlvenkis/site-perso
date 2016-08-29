<?php

namespace Generics\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
// N'oubliez pas ces deux use !
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MyProfilType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
      // Ajoutez ici tous vos champs sauf le champ published
      $builder = $builder
                      ->add('nom')
                      ->add('prenom')
                      ->add('anonyme')
                      ->add('theme', EntityType::class, array(
                            'class'    => 'GenericsCoreBundle:Theme',
                            'choice_label' => function ($theme) {
                                                    return $theme->getNom().' - '.$theme->getDescription();
                                                },
                            'placeholder' => '',
                            'required' => false
                        ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Generics\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'biblio_bundle_user';
    }

    public function getParent()
    {
      return \FOS\UserBundle\Form\Type\ProfileFormType::class;
    }
}
