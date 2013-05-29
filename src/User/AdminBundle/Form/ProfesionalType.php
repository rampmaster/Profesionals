<?php

namespace User\AdminBundle\Form;

use Core\UserBundle\Form\UserAddType;
use Core\UserBundle\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new UserAddType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Professional'
        ));
    }

    public function getName()
    {
        return 'user_adminbundle_professionaltype';
    }
}
