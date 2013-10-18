<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RadiografiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('servicioRadiologia', null, array(
                'required' => false,   'attr' => array('class' => 'span6'),
                'label' => 'Servicio de Radiologia'
            ))
            ->add('rxToraxFrente', null, array(
                'required' => false, 'label' => 'Rx Tórax Frente'
            ))
            ->add('rxToraxPerfix', null, array(
                'required' => false, 'label' => 'Rx Tórax Perfil'
            ))
            ->add('simpleAbdomen', null, array(
                'required' => false, 'label' => 'Rx simple abdomen'
            ))
            ->add('urografiaDescendente', null, array(
                'required' => false, 'label' => 'Urografía intravenosa'
            ))
            ->add('cistouretrografiaMicionales', null, array(
                'required' => false, 'label' => 'Cistouretrografías miccionales'
            ))
            ->add('cistouretrografiasPostmiccional', null, array(
                'required' => false, 'label' => 'Cistouretrografía posmiccional'
            ))
            ->add('cistouretrografiaRetrograda', null, array(
                'required' => false, 'label' => 'Cistouretrografías retrógradas'
            ))
            ->add('prostaica', null, array(
                'required' => false, 'label' => 'RMN prostática endorectal + espectrometría'
            ))
            ->add('rmn_prostatica', null, array(
                'required' => false, 'label' => 'RMN Prostática'
            ))
            ->add('ecografiaRenal', null, array(
                'required' => false, 'label' => 'Ecografía Renal'
            ))
            ->add('ecografiaVesical', null, array(
                'required' => false, 'label' => 'Ecografía Vesical pre y posmiccional'
            ))
            ->add('ecografiaProstatica', null, array(
                'required' => false, 'label' => 'Ecografía Prostática suprapúbica'
            ))
            ->add('ecografiaProstaticaEndorectal', null, array(
                'required' => false, 'label' => 'Ecografía Prostática endorectal'
            ))
            ->add('ecografiaEscrotal', null, array(
                'required' => false, 'label' => 'Ecografía escrotal'
            ))
            ->add('ecografiaAbdominal', null, array(
                'required' => false, 'label' => 'Ecografía abdominal'
            ))
            ->add('tacAbdominopelviano', null, array(
                'required' => false, 'label' => 'TAC Abdominopelviano'
            ))
            ->add('tacToracico', null, array(
                'required' => false, 'label' => 'TAC torácico'
            ))
            ->add('rmnAbdominal', null, array(
                'required' => false, 'label' => 'RMN abdominal Abdominopelviana'
            ))
            ->add('orientacionDiagnostica', null, array(
                'required' => false, 'label' => 'Orientación diagnóstica',
                'attr' => array('class' => 'span6')
            ))

            ->add('extra', null, array(
                'required' => false, 'label' => 'Campo extra',
                'attr' => array('class' => 'span6')
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Radiografia'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_radiografiatype';
    }
}
