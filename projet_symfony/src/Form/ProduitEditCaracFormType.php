<?php

namespace App\Form;

use App\Entity\Caracteristiques;
use App\Entity\Produit;
use App\Entity\TypeCaracteristiques;
use App\Repository\CaracteristiquesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitEditCaracFormType extends AbstractType
{
    public const PRODUIT = null;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->PRODUIT = $options['data'];
        $builder
            ->add('caracteristiques', EntityType::class, [
                'expanded' => true,
                'label' => 'Caractéristiques',
                'multiple' => true,
                'choice_label' => 'nom',
                'class' => Caracteristiques::class,
                'query_builder' => function (CaracteristiquesRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->join('c.typeCaracteristiques', 't')
                        ->join('t.categories', 'cat')
                        ->where('cat.id ='.$this->PRODUIT->getCategorie()->getId());

                }
            ])
            ->add('envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
