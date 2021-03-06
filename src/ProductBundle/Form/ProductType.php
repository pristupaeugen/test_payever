<?php

// src/ProductBundle/Form/ProductType.php
namespace ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use AppBundle\Entity\Product;

/**
 * Class ProductType
 * @package ProductBundle\Form
 */
class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',       TextType::class)
            ->add('description', TextType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => Product::class,
            'allow_extra_fields' => true
        ));
    }

    /**
     * Get block prefix
     * @return null
     */
    public function getBlockPrefix() {
        return null;
    }
}