<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasserCommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'required'=> false])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'required'=> false])
            ->add('adresseMail', EmailType::class, ['label' => 'Email', 'required'=> false])
            ->add('telephone', TelType::class, ['label' => 'Téléphone', 'required'=> false])
            ->add('adresse', TextareaType::class, ['label' => 'Adresse', 'required'=> false])
            ->add('codePostale', TextType::class, ['label' => 'Code Postale', 'required'=> false])
            ->add('ville', TextType::class, ['label' => 'Ville', 'required'=> false])
            ->add('Modifier', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
