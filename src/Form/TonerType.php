<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Supplier;
use App\Entity\Toner;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TonerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('articleNumber', TextType::class, ['label' => 'Art. Nr.'])
            ->add('position', TextType::class, ['label' => 'Position'])
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Bezeichnung'
            ])
            ->add('quantity', NumberType::class, ['label' => 'Anzahl/Menge'])
            ->add('color', TextType::class, ['label' => 'Farbe'])
            ->add('addedAt', DateTimeType::class, ['label' => 'Angelegt am']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Toner::class,
        ]);
    }
}
