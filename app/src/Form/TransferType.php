<?php

namespace App\Form;

use App\Entity\Transfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('srcAccountNumber',TextType::class,[
                'label'=>"Depuis le compte N°",
                'attr'=>[
                    'placeholder'=>'1234'
                ]
            ])
            ->add('destAccountNumber',TextType::class,[
                'label'=>"Vers le compte N°",
                'attr'=>[
                    'placeholder'=>'5678'
                ]
            ])
            ->add('amount',IntegerType::class,[
                'label'=>"Montant",
                'attr'=>[
                    'placeholder'=>'200'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=>Transfer::class
        ]);
    }
}
