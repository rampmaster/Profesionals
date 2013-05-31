<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesionalEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('start_date', 'genemu_jquerydate', array(
                'widget' => 'single_text',
                'label' => 'Dia de la consulta'
            ))
            ->add('hour_date', 'time', array(
                'label' => 'Hora de la consulta'
            ))
            ->add('duration','choice', array(
                'label' => 'Duración de la consulta',
                'choices' => array('PT15M' => '15 Minutos', 'PT30M' => '30 Minutos', 'PT1H' => '1 Hora')
            ))
            ->add('notify', 'choice', array(
                'label' => '¿Avisar por email al cliente?',
                'choices' => array(true => 'Avisar por email', false => 'No avisar por email')
            ))
            ->add('notify_at', 'choice', array(
                'label' => 'Fecha del aviso',
                'choices' => array(
                    'PT1H' => '1 hora antes',
                    'P1D' => '1 dia antes del evento'
                )
            ))
            ->add('client', 'genemu_jqueryselect2_entity', array(
                'class' => 'User\ClientBundle\Entity\Client',
                'property' => 'user.name',
                'label' => 'Cliente asociado a la consulta'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\ProfesionalEvent'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_profesionaleventtype';
    }
}
