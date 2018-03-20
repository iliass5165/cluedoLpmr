<?php

namespace Lpmr\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class GroupeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('etudiants', EntityType::class,  array(
                    "required" => false,
                    'by_reference' => false,
                    'class'   => 'LpmrUserBundle:Etudiant','multiple' => true, 'choice_label' => 'nom'), array('attr' => array('class' => 'input-field col s12 ')))
                ->add("save", SubmitType::class,[
                    "label" => "Valider",
                    "attr" => ["class" => "waves-effect waves-light btn"]
                ]);
                //->add('nbpointglobal')
                //->add('annee')
                //->add('code');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lpmr\UserBundle\Entity\Groupe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lpmr_userbundle_groupe';
    }


}
