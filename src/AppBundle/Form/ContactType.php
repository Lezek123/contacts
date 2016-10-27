<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\IsTrue;

/**
 * Class ContactType
 */
class ContactType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = $options['validation_groups'] ? $options['validation_groups'] : array();
        if (in_array('delete', $groups, true)) {
            $builder->add('deleteConfirm', HiddenType::class, array(
                'empty_data' => false,
                'mapped' => false,
                'constraints' => array(
                    new IsTrue(),
                ),
            ));
            if (in_array('debug', $groups, true)) {
                $builder->add('permanentDelete', CheckboxType::class, array(
                    'required' => false,
                    'mapped' => false,
                    'label' => 'permanent.delete',
                ));
            }
        } else {
            $builder->add('firstname');
            $builder->add('lastname');
            $builder->add('details', CollectionType::class, array(
                'entry_type' => ContactDetailType::class,
                'entry_options' => array(
                    'label' => false,
                    'validation_groups' => $groups, /* Pass groups to ContactDetailType */
                ),
                'label' => 'details',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ));
            if (in_array('debug', $groups, true)) {
                $builder->add('isDeleted', CheckboxType::class, array(
                    'value' => 'isDeleted',
                    'label' => 'deleted',
                    'required' => false,
                ));
                $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                    /* Remove not posted details if in debug mode */
                    $submittedData = $event->getData();
                    $form = $event->getForm();
                    $contact = $form->getData();
                    foreach ($contact->getDetails() as $detail) {
                        if (!isset($submittedData['details'][$detail->getId()])) {
                            $contact->removeDetail($detail, true);
                        }
                    }
                    $form->setData($contact);
                });
            }
        }
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }
}
