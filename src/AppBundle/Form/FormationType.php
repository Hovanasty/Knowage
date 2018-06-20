<?php

namespace AppBundle\Form;


use AppBundle\Entity\Formation;
use Doctrine\DBAL\Types\TextType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class FormationType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('content', \Symfony\Component\Form\Extension\Core\Type\TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Formation::class,
        ));

    }

}