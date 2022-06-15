<?php

namespace App\Form;

use App\Entity\Admin;
use App\Entity\Clients;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, ['mapped' => false, 'label' => 'Pseudo', 'required'=> false])
            ->add('nom', TextType::class, ['label' => 'Nom', 'required'=> false])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'required'=> false])
            ->add('adresse', TextareaType::class, ['label' => 'Adresse', 'required'=> false])
            ->add('codePostale', TextType::class, ['label' => 'Code Postale', 'required'=> false])
            ->add('ville', TextType::class, ['label' => 'Ville', 'required'=> false])
            ->add('adresseMail', EmailType::class, ['label' => 'Email', 'required'=> false])
            ->add('telephone', TelType::class, ['label' => 'Telephone', 'required'=> false])
            ->add('MDP', PasswordType::class, ['mapped' => false, 'label' => 'Mot De Passe', 'required'=> false])
            ->add('Confirmmdp', PasswordType::class, ['mapped' => false, 'label' => 'Confirmation de Mot de Passe', 'required'=> false])
            ->add('Soumettre', SubmitType::class );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
