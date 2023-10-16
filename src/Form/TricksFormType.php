<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\TricksGroup;
use App\Form\TricksImageType;
use App\Form\TricksVideoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TricksFormType extends AbstractType
{   
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,['constraints' => [
                new NotBlank([
                    'message' => "Merci de renseigner un nom pour le tricks",
                ]),
                new Length([
                    'min' => 4,
                    'minMessage' => 'Le nom du tricks doit faire au moins {{ limit }} caractères',
                ]),
            ],])
            ->add('description',TextType::class,['constraints' => [
                new NotBlank([
                    'message' => "Merci de renseigner une description pour le tricks",
                ]),
                new Length([
                    'min' => 20,
                    'minMessage' => 'La description du tricks doit faire au moins {{ limit }} caractères',
                ]),
            ],])
            ->add('tricksGroup', EntityType::class, [
                'class' => TricksGroup::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un TricksGroup',
                'choices' => $this->getTricksGroups(),
            ])
            ->add('tricksImages', CollectionType::class, [
                'entry_type' => TricksImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'label' => false
            ])
            ->add('tricksVideos', CollectionType::class, [
                'entry_type' => TricksVideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'label' => false
            ])
        ;
    }

    private function getTricksGroups() : array
    {
        $tricksGroups = $this->entityManager->getRepository(TricksGroup::class)->findAll();

        $choices = [];
        foreach ($tricksGroups as $tricksGroup) {
            $choices[$tricksGroup->getName()] = $tricksGroup;
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
