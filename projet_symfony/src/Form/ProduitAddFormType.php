<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Fournisseur;
use App\Entity\GroupProduit;
use App\Entity\Produit;
use App\Entity\TypeCaracteristiques;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('is_active', CheckboxType::class, ['required' => false,'label' => "Cocher pour que votre produit soit actif dans le catalogue"])
            ->add('nom', TextType::class, ['label' => 'Nom du produit'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('groupProduit', EntityType::class, ['expanded'=> true, 'multiple' => true, 'class' => GroupProduit::class,'required' => false ,'label' => 'Le nom du groupement de produit si celui-ci fait parti d\'un ensemble (pour le faire apparaitre dans la page d\'Acueil prendre le groupe page1'])
            ->add('prix', NumberType::class, ['label' => 'Prix'])
            ->add('stock', IntegerType::class, ['label' => 'Quantité en stock'])
            ->add('image', FileType::class, [
                'label' => 'Image ',
                'required' => false,
                'mapped' => false
            ])
            ->add('fournisseur', EntityType::class, ['label' => 'Fournisseur', 'choice_label' => 'nom', 'class' => Fournisseur::class])
            ->add('categorie')
            ->add('Soumettre', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
