<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StylesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Logotipo',
                'attr' => array('class' => 'span5'),
                'required'=>false
            ))
            ->add('color_bg_main', 'genemu_jquerycolor', array(
                'label' => 'Color del Fondo (1)',
            ))
            ->add('color_bg_secd', 'genemu_jquerycolor', array(
                'label' => 'Color del Fondo (2)',
            ))
            ->add('color_button', 'genemu_jquerycolor', array(
                'label' => 'Color extra',
            ))
            ->add('color_text_button', 'genemu_jquerycolor', array(
                'label' => 'Color extra',
            ))
            ->add('raw_css', 'textarea', array(
                'label' => 'CSS Extra',
                'attr' => array('class'=>"span12","rows"=>10),
                'required' => false
            ))
            ->add('professional', new ProfessionalUsernameType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Styles'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_stylestype';
    }
}

