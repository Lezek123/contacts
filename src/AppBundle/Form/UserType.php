<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.10.16
 * Time: 14:11
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = $options['validation_groups'];
        if (in_array('register', $groups, true) or in_array('edit', $groups, true)) {
            $builder->add('username', TextType::class, array('label' => 'username'));
            $builder->add('email', EmailType::class, array('label' => 'E-mail'));
        }
        if (in_array('register', $groups, true)) {
            $builder->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array('label' => 'password'),
                'second_options' => array('label' => 'password.repeat'),
            ));
        }
        if (in_array('edit', $groups, true)) {
            $builder->add('roles', CollectionType::class, array(
                'label' => 'roles',
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => ChoiceType::class,
                'entry_options' => array(
                    'choices' => array('user' => "ROLE_USER", 'admin' => "ROLE_ADMIN"),
                    'label' => false,
                ),
            ));
        }
        if (in_array('delete', $groups, true)) {
            $builder->add('deleteConfirm', HiddenType::class, array(
                'empty_data' => false,
                'mapped' => false,
                'constraints' => array(
                    new IsTrue(),
                ),
            ));
        }
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}
