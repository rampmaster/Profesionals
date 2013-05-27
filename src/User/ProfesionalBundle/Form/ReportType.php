<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'Título',
                'attr' => array('class' => 'span5')
            ))
            ->add('type', 'choice', array(
                'label' => 'Tipo de reporte',
                'choices' => array('seguimiento' => 'Seguimiento', 'nota' => 'Nota'),

            ))
            ->add('text', 'textarea', array(
                'label' => 'Texto',
                'attr' => array('class' => 'textarea', 'style' => 'width: 810px; height: 200px')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Report'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_reporttype';
    }
}
