<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\TypeCaracteristiques;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCategoryFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $builder
            ->add('nom', TextType::class, ['label' => 'Intitulé de la catégorie'])
            ->add('typeCaracteristique', EntityType::class, ['expanded'=> true, 'label' => 'Types de Caractéristiques', 'multiple' => true, 'choice_label'=> 'nom', 'class' => TypeCaracteristiques::class])
            ->add('Soumettre', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
