<?php

namespace AppBundle\Form;

use AppBundle\Entity\FieldType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class ContactDetailType
 */
class ContactDetailType extends AbstractType
{
    protected $fieldsRep;

    /**
     * ContactDetailType constructor.
     * @param EntityRepository $fieldsRep
     */
    public function __construct(EntityRepository $fieldsRep)
    {
        $this->fieldsRep = $fieldsRep;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = $options['validation_groups'] ? $options['validation_groups'] : array();
        $builder->add('fieldType', EntityType::class, array(
            'class' => 'AppBundle:FieldType',
            'choice_label' => 'name',
            'choice_attr' => function (FieldType $ft) {
                return ['data-type' => $ft->getFieldType()];
            },
            /* Metoda pomocnicza */
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('f')->where('f.isActive = true');
            },
            'label' => false,
            'choice_translation_domain' => 'messages',
        ));
        $builder->add('value', HiddenType::class);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $contactDetail = $event->getData();
            $fieldType =  $this->fieldsRep->find($contactDetail['fieldType']);
            if ($fieldType) {
                $contactDetail['value'] = $contactDetail[$fieldType->getFieldType()];
            }
            $event->setData($contactDetail);
        });
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $contactDetail = $event->getData();
            if ($contactDetail) {
                $form = $event->getForm();
                $form->get($contactDetail->getType())->setData($contactDetail->getValue());
            }
        });
        $fieldTypes = $this->fieldsRep->findByIsActive(true);
        foreach ($fieldTypes as $fieldType) {
            $builder->add($fieldType->getFieldType(), $fieldType->getTypeClassName(), array('mapped' => false));
        }
        if (in_array('debug', $groups, true)) {
            $builder->add('isDeleted', CheckboxType::class, array(
                'value' => 'isDeleted',
                'label' => 'deleted',
                'required' => false,
            ));
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContactDetail',
        ));
    }
}
