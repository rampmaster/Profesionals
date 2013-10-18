<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UrodinamicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('diagnostico', 'textarea', array(
                'label' => 'Diagnóstico',
                'attr' => array('class' => 'textarea', 'style' => 'width: 810px; height: 200px')
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Urodinamico'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_urodinamicotype';
    }
}
