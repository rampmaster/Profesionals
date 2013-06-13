<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProfessionalUsernameType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->
            add('user', new UsernameType());

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Professional'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_professionalusernametype';
    }
}
