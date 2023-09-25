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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('name')
            ->add('description')
            ->add('tricksGroup', EntityType::class, [
                'class' => TricksGroup::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un TricksGroup',
                'choices' => $this->getTricksGroups(),
            ])
            ->add('tricksImages', LiveCollectionType::class, [
                'entry_type' => TricksImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'label' => false
            ])
            ->add('tricksVideos', LiveCollectionType::class, [
                'entry_type' => TricksVideoType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'label' => false
            ])
        ;
    }

    private function getTricksGroups()
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
