<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Subcategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add('category', EntityType::class, [
                'placeholder' => 'Choose a category...',
                'label' => false,
                'class' => Category::class,
                'choice_label' => 'name'
            ])
        ;

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addSubcategoryField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addSubcategoryField($form, null);
            }
        );
    }

    private function addSubcategoryField(FormInterface $form, ?Category $category)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('subcategory', EntityType::class, null, [
            'class' => Subcategory::class,
            'label' => false,
            'placeholder' => $category ? 'Choose a subcategory...' : "Choose a category first !",
            'auto_initialize' => false,
            'choices' => $category ? $category->getSubcategories() : []
        ]);

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
