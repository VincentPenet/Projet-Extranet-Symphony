<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('target', null,[
                'label'=>'Choisir le destinataire parmi la liste',
                'choice_label'=> 'pseudo',
                'attr'=>[
                    'placeholder' => 'Destinataire'
                ],
            ])

            ->add('Object', null, [
                'label'=>'Objet du message',
                'attr'=>[
                    'autofocus' => true
                    ],
                'attr'=>[
                    'placeholder' => 'Indiquer l\'objet du message'
                    ],
                ])

            ->add('message', null, [
                'label'=>'Contenu du message',
                'attr'=>['rows' => 10, 'cols' => 50 ],
                'attr'=>[
                    'placeholder' => 'Ecrire votre message'
                ],
            ])


            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
