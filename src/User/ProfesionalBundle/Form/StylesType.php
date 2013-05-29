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
            ->add('color_bg_main', null, array(
                'label' => 'Color del Fondo (1)',
            ))
            ->add('color_bg_secd', null, array(
                'label' => 'Color del Fondo (2)',
            ))
            ->add('color_extra', null, array(
                'label' => 'Color extra',
            ))
            ->add('raw_css', 'textarea', array(
                'label' => 'CSS Extra',
                'attr' => array('class'=>"span12","rows"=>10)
            ))
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
