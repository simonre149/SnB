<?php

namespace App\Form;

use App\Entity\Subcategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false
            ])
            ->add('min_price', NumberType::class, [
                'label' => false, 'required' => false
            ])
            ->add('max_price', NumberType::class, [
                'label' => false, 'required' => false
            ])
            ->add('subcategory', EntityType::class, [
                'placeholder' => 'Choose a subcategory...',
                'label' => false,
                'class' => Subcategory::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
