<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Quel nom souhaitez-vous donner à votre adresse',
                'attr'=>[
                    'placeholder'=>'Nommez votre adresse'
                ]
            ])

            ->add('firstname',TextType::class,[
                'label'=>'Prénom',
                'attr'=>[
                    'placeholder'=>'Entrer votre prénom'
                ]
            ])
            ->add('lastname',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'Entrer votre nom'
                ]
            ])

            ->add('company',TextType::class,[
                'label'=>'Société',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Entrer le nom de votre société '
                ]
            ])
            ->add('address',TextType::class,[
                'label'=>'Adresse',
                'attr'=>[
                    'placeholder'=>'47 rue de ....'
                ]
            ])

            ->add('postal',TextType::class,[
                'label'=>'Code postal',
                'attr'=>[
                    'placeholder'=>'5035'
                ]
            ])

            ->add('city',TextType::class,[
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Entrer votre ville'
                ]
            ])

            ->add('pays',CountryType::class,[
                'label'=>'Pays',
                'attr'=>[
                    'placeholder'=>'Votre pays'
                ]
            ])

            ->add('phone',TelType::class,[
                'label'=>'Numéro de télèphone',
                'attr'=>[
                    'placeholder'=>'Entrer votre numéro'
                ]
            ])

            ->add('submit',SubmitType::class,[
                'label'=>"Valider",
                'attr'=>[
                    'class'=>'btn-block btn-info']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
