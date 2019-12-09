<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Good;
use App\Entity\Subcategory;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('imageFile', FileType::class, [
                'required' => false,
            ])
            ->add('city')
            ->add('postalCode')
            ->add('phone')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category',
                'mapped' => false,
                'required' => false
            ]);

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
            'placeholder' => $category ? 'Choose a subcategory' : "Choose a category first !",
            'required' => false,
            'auto_initialize' => false,
            'choices' => $category ? $category->getSubcategories() : []
        ]);

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Good::class,
        ]);
    }
}
