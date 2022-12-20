<?php

namespace App\Form;

use App\Entity\Announces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnouncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('title', null, [
                'label' => 'Titre de l\'annonce',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Veuillez entrer le titre de l\'annonce',
                ],
            ])

            ->add('categories', ChoiceType::class, [
                'label'=>'Catégorie de l\'annonce',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Choisir la catégorie'
                ],
                'choices'  => [
                    '' => null,
                    'Stage' => 'Stage',
                    'Alternance' => 'Alternance',
                    'Contrat pro' => 'Contrat pro',
                    'Emploi' => 'Emploi',
                ],
            ])

            ->add('description',TextareaType::class , [
                'label' => 'Description de l\'annonce',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Décrire le poste',
            ], ])

            ->add('originalLink', null, [
                'label' => 'Lien de l\'annonce',
                'attr' => [
                    'placeholder' => 'Ajouter le lien de l\'annonce'
                ],])

            ->add('nameCompany', null, [
                'label' => 'Entreprise',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Entreprise'
                ],])

            ->add('adressCompany', null, [
                'label' => 'Adresse de l\'entreprise',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Adresse'
                ],])
            
            ->add('adressAdditional', null, [
                'label' => ' Adresse complémentaire',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Adresse complémentaire'
                ],])
            
            ->add('zipCode', null, [
                'label' => 'Code postal',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Code postal'
                ],])
            
            ->add('city', null, [
                'label' => 'Ville',
                'attr'=>[
                    'autofocus' => true,
                    'placeholder' => 'Ville'
                ],])
            
            // ->add('user')
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announces::class,
        ]);
    }
}
