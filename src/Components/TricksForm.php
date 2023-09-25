<?php

namespace App\Components;

use App\Entity\Tricks;
use App\Form\TricksFormType;
use App\Form\TricksVideoType;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsLiveComponent]
class TricksForm extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'formData')]
    public ?Tricks $tricks;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            TricksFormType::class,
            $this->tricks
        );
    }
}
