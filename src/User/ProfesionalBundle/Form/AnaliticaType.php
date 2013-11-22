<?php

namespace User\ProfesionalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnaliticaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hemogramaCompleto', null, array(
                'required' => false, 'label' => 'HEMOGRAMA COMPLETO',
            ))
            ->add('plaquetas', null, array(
                'required' => false, 'label' => 'P. COAGULACION'
            ))
            ->add('rh', null, array(
                'required' => false, 'label' => 'RH'
            ))
            ->add('reticulocitos', null, array(
                'required' => false, 'label' => 'Reticulocitos'
            ))
            ->add('sideremia', null, array(
                'required' => false, 'label' => 'Sideremia'
            ))
            ->add('ferritina', null, array(
                'required' => false, 'label' => 'Ferritina'
            ))
            ->add('transferrina', null, array(
                'required' => false, 'label' => 'Transferrina'
            ))
            ->add('b12', null, array(
                'required' => false, 'label' => 'Vit. B12'
            ))
            ->add('acido_folico', null, array(
                'required' => false, 'label' => 'Ácido Fólico'
            ))

            ->add('fibrinogeno', null, array(
                'required' => false, 'label' => 'Fibrinógeno'
            ))

            ->add('proteinograma', null, array(
                'required' => false, 'label' => 'Proteinograma'
            ))
            ->add('dhea', null, array(
                'required' => false, 'label' => 'DHEA'
            ))->add('shbg', null, array(
                'required' => false, 'label' => 'SHNBG'
            ))
            ->add('gh', null, array(
                'required' => false, 'label' => 'GH'
            ))
            ->add('acth', null, array(
                'required' => false, 'label' => 'ACTH'
            ))
            ->add('cortisol', null, array(
                'required' => false, 'label' => 'Cortisol'
            ))
            ->add('aldosterona', null, array(
                'required' => false, 'label' => 'Aldosterona'
            ))
            ->add('anglotensina', null, array(
                'required' => false, 'label' => 'Angiotensina'
            ))
            ->add('vitamina_D', null, array(
                'required' => false, 'label' => 'Vit D'
            ))
            ->add('vitamina_D3', null, array(
                'required' => false, 'label' => 'Vit D3'
            ))

            ->add('ca_153', null, array(
                'required' => false, 'label' => 'CA 153'
            ))

            ->add('ca_199', null, array(
                'required' => false, 'label' => 'CA 19.9'
            ))

            ->add('ca_125', null, array(
                'required' => false, 'label' => 'CA D125'
            ))
            ->add('creatinina', null, array(
                'required' => false, 'label' => 'CREATININA'
            ))
            ->add('uricemia', null, array(
                'required' => false, 'label' => 'URICEMIA'
            ))
            ->add('gruposanguineo', null, array(
                'required' => false, 'label' => 'GRUPO SANGUINEO'
            ))
            ->add('proteinas', null, array(
                'required' => false, 'label' => 'PROTEINAS'
            ))
            ->add('colesterolHdl', null, array(
                'required' => false, 'label' => 'COLESTEROL HDL'
            ))
            ->add('colesterolLdl', null, array(
                'required' => false, 'label' => 'COLESTEROL LDL'
            ))
            ->add('glicemia', null, array(
                'required' => false, 'label' => 'GLICEMIA'
            ))
            ->add('proteinaCReactiva', null, array(
                'required' => false, 'label' => 'PROTEINA C REACTIVA'
            ))
            ->add('hepatitisBC', null, array(
                'required' => false, 'label' => 'SEROLOGIA HEPATITIS B Y C'
            ))
            ->add('tg', null, array(
                'required' => false, 'label' => 'TG'
            ))
            ->add('gotGpt', null, array(
                'required' => false, 'label' => 'GOT, GPT'
            ))
            ->add('bt', null, array(
                'required' => false, 'label' => 'BT'
            ))
            ->add('ggt', null, array(
                'required' => false, 'label' => 'GGT'
            ))
            ->add('na', null, array(
                'required' => false, 'label' => 'Na'
            ))
            ->add('k', null, array(
                'required' => false, 'label' => 'K'
            ))
            ->add('p', null, array(
                'required' => false, 'label' => 'P'
            ))
            ->add('ca', null, array(
                'required' => false, 'label' => 'Ca'
            ))
            ->add('pth', null, array(
                'required' => false, 'label' => 'PTH'
            ))
            ->add('alcalinas', null, array(
                'required' => false, 'label' => 'F. Alcalinas'
            ))
            ->add('vsg', null, array(
                'required' => false, 'label' => 'VSG'
            ))
            ->add('psa_libre', null, array(
                'required' => false, 'label' => 'PSA Libre'
            ))
            ->add('psa_total', null, array(
                'required' => false, 'label' => 'PSA Total'
            ))
            ->add('testoterona_libre', null, array(
                'required' => false, 'label' => 'TESTOSTERONA Libre'
            ))
            ->add('testoterona_total', null, array(
                'required' => false, 'label' => 'TESTOSTERONA Total'
            ))
            ->add('fsh', null, array(
                'required' => false, 'label' => 'FSH'
            ))
            ->add('lh', null, array(
                'required' => false, 'label' => 'LH'
            ))
            ->add('prl', null, array(
                'required' => false, 'label' => 'PRL'
            ))
            ->add('colesterolTotal', null, array(
                'required' => false, 'label' => 'Colesterol Total'
            ))
            ->add('hcg', null, array(
                'required' => false, 'label' => 'Beta-HCG'
            ))
            ->add('ldh', null, array(
                'required' => false, 'label' => 'LDH'
            ))
            ->add('fetoproteina', null, array(
                'required' => false, 'label' => 'A-FETOPROTEINA'
            ))
            ->add('cea', null, array(
                'required' => false, 'label' => 'CEA'
            ))
            ->add('tsh', null, array(
                'required' => false, 'label' => 'TSH, T3, T4'
            ))
            ->add('sedimento_orina', null, array(
                'required' => false, 'label' => 'SEDIMENTO'
            ))
            ->add('cultivo_orina', null, array(
                'required' => false, 'label' => 'CULTIVO'
            ))
            ->add('antibiograma_orina', null, array(
                'required' => false, 'label' => 'ANTIBIOGRAMA'
            ))
            ->add('pca3_orina', null, array(
                'required' => false, 'label' => 'PCA 3'
            ))
            ->add('seminograma_semen', null, array(
                'required' => false, 'label' => 'SEMINOGRAMA'
            ))
            ->add('seminocultivo_semen', null, array(
                'required' => false, 'label' => 'SEMINOCULTIVO'
            ))
            ->add('orina_24h_calcio', null, array(
                'required' => false, 'label' => 'Calcio'
            ))
            ->add('orina_24h_fosforo', null, array(
                'required' => false, 'label' => 'Fósforo'
            ))
            ->add('orina_24h_uratos', null, array(
                'required' => false, 'label' => 'Uratos'
            ))
            ->add('orina_24h_citratos', null, array(
                'required' => false, 'label' => 'Citratos'
            ))
            ->add('orina_24h_magnesio', null, array(
                'required' => false, 'label' => 'Magnesio'
            ))
            ->add('orina_24h_proteinas_totales', null, array(
                'required' => false, 'label' => 'Proteinas totales'
            ))
            ->add('orina_24h_volumen', null, array(
                'required' => false, 'label' => 'Volumen'
            ))
            ->add('orina_24h_oxalatos', null, array(
                'required' => false, 'label' => 'Oxalatos'
            ))
            ->add('extra', null, array(
                'required' => false, 'label' => 'Anotaciones extra',
                'attr' => array('class' => 'span8')
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'User\ProfesionalBundle\Entity\Analitica'
        ));
    }

    public function getName()
    {
        return 'user_profesionalbundle_analiticatype';
    }
}
