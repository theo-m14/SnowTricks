<?php

namespace App\Form;

use App\Entity\TricksVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TricksVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('link', TextType::class, [
                'label' => 'URL de la vidéo',
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci de renseigner une url pour les vidéos",
                    ]),
                    new Length([
                        'min' => 20,
                        'minMessage' => 'La description du tricks doit faire au moins {{ limit }} caractères',
                    ]),
                    new Url([
                        'message' => "L'url doit être valide et commencer par 'https://' ou 'www'",
                        'protocols' => ['https', 'http'],
                        'relativeProtocol' => true,
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TricksVideo::class,
        ]);
    }
}
