<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class CustomNoteType
 */
class CustomNoteType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'attr' => array('placeholder' => 'eg. Lorem ipsum dolor'),
            'required' => false,
            'constraints' => array(
                new Length(array('max' => 255)),
            ),
        ));
    }

    /**
     * @return string Parent class name
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
