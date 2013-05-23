<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Nombre'
            ))
            ->add('surname', null, array(
                'label' => 'Apellidos'
            ))
            ->add('is_male', 'choice', array(
                'label' => 'Sexo',
                'choices' => array(true => 'Hombre', false => 'Mujer')
            ))

            ->add('mobile', null, array(
                'label' => 'Número de móvil'
            ))
            ->add('plainPassword', 'repeated',array(
                'label' => 'Contraseña'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'core_userbundle_usertype';
    }
}
