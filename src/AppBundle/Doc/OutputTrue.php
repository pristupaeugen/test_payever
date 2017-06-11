<?php

namespace AppBundle\Doc;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class OutputTrue. Used for NelmioApiDoc.
 * @package AppBundle\Doc
 */
class OutputTrue extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', CheckboxType::class, ['description' => 'it takes True']);
    }

    /**
     * Get block prefix
     * @return null
     */
    public function getBlockPrefix() {
        return null;
    }
}