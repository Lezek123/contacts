<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class CustomMobileType
 */
class CustomMobileType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'attr' => array('placeholder' => 'eg. +48 888 888 888'),
            'required' => false,
            'constraints' => array(
                new Regex(array('pattern' => "/(^[\+]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+$|^$)/", 'message' => 'invalid.mobile')),
                new Length(array('max' => 25)),
            ),
        ));
    }

    /**
     * @return string Parent class name
     */
    public function getParent()
    {
        return TextType::class;
    }
}
