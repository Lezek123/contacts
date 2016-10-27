<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class CustomEmailType
 */
class CustomEmailType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'attr' => array('placeholder' => 'eg. example@example.com'),
            'required' => false,
            'constraints' => array(
                new Email(),
                new Length(array('max' => 128)),
            ),
        ));
    }

    /**
     * @return string Parent class name
     */
    public function getParent()
    {
        return EmailType::class;
    }
}
