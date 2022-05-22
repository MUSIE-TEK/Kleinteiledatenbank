<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

//Schablone für Form
class ArticleType extends AbstractType
{
    //baut die Form zusammen
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //folgende Untertypen sollen deklariert werden
        $builder->add('artnumber', IntegerType::class)
            ->add('name', TextType::class)
            //referenziert auf andere Tabelle/Entity
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'label' => 'Kategorie'
            ])
            ->add('price', NumberType::class)
            ->add('supplier', EntityType::class, [
                'class'        => Supplier::class,
                'choice_label' => 'name'
            ])
            ->add('minimumstock', IntegerType::class)
            ->add('orderid', IntegerType::class)
            //required false = darf leer bleiben
            ->add('approval', IntegerType::class, ['required' => false])
            ->add('imac', IntegerType::class, ['required' => false])
            ->add('comment', TextType::class, ['required' => false])
            ->add('compatibilities', TextType::class, ['required' => false])
            ->add('minimumorderamount', IntegerType::class)
            ->add('archive', CheckboxType::class, ['required' => false])
            ->add('archivedate', DateTimeType::class)
            ->add('inventory', TextType::class, ['required' => false])
        ;
    }

    //Deklariert den type, um den es sich handelt
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
