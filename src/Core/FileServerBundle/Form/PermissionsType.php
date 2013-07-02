<?php

namespace Core\FileServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PermissionsType extends AbstractType
{

    private $file;
    public function __construct($file){
        $this->file = $file;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //print_r($this->file->getOwner());
        $clients = array();
        foreach($this->file->getOwner()->getProfessional()->getClients() as $c){
            $clients[$c->getUser()->getId()] = $c->getUser()->getName();
        }

        $builder
            ->add('user', 'genemu_jqueryselect2_choice', array(
                'label' => 'Selecciona el cliente',
                'choices' => $clients
            ))
            ->add('permission', 'choice', array(
                'label' => 'Tipo de acceso',
                'choices' => array(4 => 'Acceso permitido', 0 => 'Acceso no permitido')
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\FileServerBundle\Entity\Permissions'
        ));
    }

    public function getName()
    {
        return 'core_fileserverbundle_permissionstype';
    }
}
