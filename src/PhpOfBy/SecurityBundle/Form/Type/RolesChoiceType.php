<?php

namespace PhpOfBy\SecurityBundle\Form\Type;

use PhpOfBy\SecurityBundle\Service\RolesServiceInterface;
use Symfony\Component\Form;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolesChoiceType extends Form\AbstractType
{
    /** @var RolesServiceInterface */
    private $rolesService;

    /**
     * @param RolesServiceInterface $rolesService
     */
    public function __construct(RolesServiceInterface $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return Form\Extension\Core\Type\ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'choices' => $this->rolesService->getRoles(),
            'multiple' => true,
        ]);
    }
}
