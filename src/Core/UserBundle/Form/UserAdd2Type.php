<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserAdd2Type extends AbstractType
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
            ->add('email', null, array(
                'label' => 'Email del usuario'
            ))
            ->add('file', null, array(
                'label' => 'Foto de perfil'
            ))
            ->add('is_male', 'choice', array(
                'label' => 'Sexo',
                'choices' => array(true => 'Hombre', false => 'Mujer')
            ))

            ->add('mobile', null, array(
                'label' => 'Número de móvil'
            ))
            ->add('plainPassword', 'repeated',array(
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Contraseña (comprobación)'),
                'required' => false
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
