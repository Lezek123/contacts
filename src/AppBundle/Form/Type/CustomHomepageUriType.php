<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class CustomHomepageUriType
 */
class CustomHomepageUriType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'attr' => array('placeholder' => 'eg. http://example.com'),
            'required' => false,
            'constraints' => array(
                new Url(),
                new Length(array('max' => 128)),
            ),
        ));
    }

    /**
     * @return string Parent class name
     */
    public function getParent()
    {
        return UrlType::class;
    }
}
