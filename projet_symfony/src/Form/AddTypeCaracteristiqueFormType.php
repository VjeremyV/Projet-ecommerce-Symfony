<?php

namespace App\Form;

use App\Entity\TypeCaracteristiques;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTypeCaracteristiqueFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=> 'Nom du type de la caractéristique : '
            ])
//            ->add('categories', ChoiceType::class,  [
//                'label'=>'Choisir une catégorie',
//                'choices'=>
//                    [
//                        'Category 1' => '1',
//                    ]
//            ])
            ->add('Soumettre', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TypeCaracteristiques::class,
        ]);
    }
}
