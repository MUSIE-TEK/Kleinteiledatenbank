<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Vorlage für die User-Form
class UserType extends AbstractType
{
    private RoleRepository $roleRepository; //RoleRepository-Object

    //RoleRepository-Konstruktor
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    //
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $options['data'];

        //QueryBuilder
        $builder->add('email')
                ->add('roles', EntityType::class, [
                    'class'        => Role::class,
                    'data'         => $user->getRolesObject(),
                    'choices'      => $this->roleRepository->findAll(),
                    'choice_label' => 'role',
                    'multiple'     => true,
                    'mapped'       => false,
                    'choice_value' => function (?Role $entity) {
                        return $entity ? $entity->getRole() : '';
                    },
                ])
                ->add('plainPassword');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
