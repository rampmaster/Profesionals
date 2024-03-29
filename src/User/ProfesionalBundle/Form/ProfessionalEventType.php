<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProfessionalEventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('start_date', 'genemu_jquerydate', array(
                'widget' => 'single_text',
                'label' => 'Dia de la consulta'
            ))
            ->add('hour_date', 'time', array(
                'label' => 'Hora de la consulta',
                'attr' => array(
                    'class' => 'timer_container'
                )
            ))
            ->add('duration','choice', array(
                'label' => 'Duración de la consulta',
                'choices' => array('PT10M' => '10 Minutos', 'PT15M' => '15 Minutos', 'PT30M' => '30 Minutos', 'PT1H' => '1 Hora')
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
                'query_builder' => function(EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->where('c.professional = :professional')
                        ->andWhere('u.enabled = :enabled')
                        ->leftJoin('c.user', 'u')
                        ->setParameter('professional', $options['data']->getProfessional()->getId())
                        ->setParameter('enabled', true);
                },
                //'property' => 'user.name',
                'label' => 'Cliente asociado a la consulta'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\ProfessionalEvent'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_professionaleventtype';
    }
}
