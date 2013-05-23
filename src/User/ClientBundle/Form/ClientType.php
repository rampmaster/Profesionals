<?php

namespace User\ClientBundle\Form;

use Core\UserBundle\Form\UserAddType;
use Core\UserBundle\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alias', null, array(
                'label' => 'Nombre identificativo para el cliente'
            ))
            ->add('user', new UserAddType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ClientBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'user_clientbundle_clienttype';
    }
}
