<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use User\ProfesionalBundle\Entity\Professional;

class WelcomeType extends AbstractType
{

    private $professional = null;

    public function __construct(Professional $professional)
    {
        $this->professional = $professional;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $skills = array();
        foreach ($this->professional->getSkills() as $s) {
            $skills[$s->getId()] = $s->getName();
        }
        $builder

            ->add('skills', 'genemu_jqueryselect2_hidden', array(
                'label' => 'Mis habilidades',
                'configs' => array(
                    'multiple' => true,
                    'tags' => $skills,
                ),

            ))
            ->add('phone', 'text', array(
                'label' => 'Teléfono de contácto',
                'attr' => array(
                    'value' => $this->professional->getPublicPhone()
                )
            ))

            ->add('city', 'text', array(
                'label' => 'Ciudad',
                'attr' => array(
                    'value' => $this->professional->getPublicCity()
                )
            ))
            ->add('direction', 'text', array(
                'label' => 'Dirección',
                'attr' => array(
                    'value' => $this->professional->getPublicDirection()
                )
            ))
            ->add('postal', 'text', array(
                'label' => 'Código postal',
                'attr' => array(
                    'value' => $this->professional->getPublicPostal()
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

    }

    public function getName()
    {
        return 'user_profesionalbundle_welcometype';
    }
}

