<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class CustomPhoneType
 */
class CustomPhoneType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'attr' => array('placeholder' => 'eg. 77 44 444 444'),
            'required' => false,
            'constraints' => array(
                new Regex(array('pattern' => "/(^[\+]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+[\- ]*[0-9]+$|^$)/", 'message' => 'invalid.phone')),
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
