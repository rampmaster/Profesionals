<?php

namespace Core\FileServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilePropertiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'Título del archivo',
                'attr' => array(
                    'class' => 'span6'
                )
            ))
            ->add('public', 'choice', array(
                'label' => 'Tipo de acceso',
                'choices' => array(
                    false => 'Acceso restringido',
                    true => 'Todo el mundo tiene acceso'
                )
            ))

            ->add('permissions', 'collection', array(

                'type' => new PermissionsType($options['data']),
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Permisos de acceso'
                )

            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\FileServerBundle\Entity\File'
        ));
    }

    public function getName()
    {
        return 'core_fileserverbundle_filetype';
    }
}
