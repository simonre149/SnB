<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Good;
use App\Entity\Subcategory;
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
                'required' => false
            ])
            ->add('city')
            ->add('postalCode')
            ->add('phone')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choose a category',
                'mapped' => false,
                'required' => true
            ])
        ;

        $builder->get('category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event)
            {
                $form = $event->getForm();
                $this->addSubcategoryField($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event)
            {
                $data = $event->getData();
                $subcategory = $data->getSubcategory();

                $form = $event->getForm();
                if ($subcategory)
                {
                    $category = $subcategory->getCategory();
                    $this->addSubcategoryField($form, $category);
                    $form->get('category')->setData($category);
                }
                else
                {
                    $this->addSubcategoryField($form, null);
                }
            }
        );
    }

    public function addSubcategoryField(FormInterface $form, ?Category $category)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'subcategory', EntityType::class, null,
            [
                'class' => Subcategory::class,
                'placeholder' => 'Choose a subcategory',
                'required' => true,
                'auto_initialize' => false,
                'choices' => $category ? $category->getSubcategories() : []
            ]
        );
        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Good::class,
        ]);
    }
}
