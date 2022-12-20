<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('lastname', null, ['label'=>'Nom'])
        ->add('firstname', null, ['label'=>'Prénom'])
            ->add('email', null, ['label'=>'Adresse mail'])
            ->add('function', ChoiceType::class, ['label'=>'Statut',
                'choices' => [
                    '' => null,
                    'Apprenant' => 'Apprenant',
                    'Formateur' => 'Formateur',
                    'Manager' => 'Manager',

                ],

            ])
            ->add('sessionnumber', null, ['label'=>'Numéro de session'])
            ->add('trainingyear', null, ['label'=>'Année de session'])
            ->add('agreeTerms', CheckboxType::class, ['label'=>'Conditions d\'utilisation',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter nos conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options' =>['label' =>'Saisir votre mot de passe'],
                'second_options' =>['label' => 'Confirmer votre mot de passe' ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être composé de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    
                ],
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
