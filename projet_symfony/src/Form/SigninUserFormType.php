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

class SigninUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, ['mapped' => false, 'label' => 'Pseudo'])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('adresse', TextareaType::class, ['label' => 'Adresse'])
            ->add('codePostale', TextType::class, ['label' => 'Code Postale'])
            ->add('ville', TextType::class, ['label' => 'Ville'])
            ->add('adresseMail', EmailType::class, ['label' => 'Email'])
            ->add('telephone', TelType::class, ['label' => 'Telephone'])
            ->add('MDP', PasswordType::class, ['mapped' => false, 'label' => 'Mot De Passe'])
            ->add('Confirmmdp', PasswordType::class, ['mapped' => false, 'label' => 'Confirmation de Mot de Passe'])
            ->add('Soumettre', SubmitType::class );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
