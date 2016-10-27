<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldType
 *
 * @ORM\Table(name="field_types", indexes={@ORM\Index(name="IX_field_types_1", columns={"is_active"}), @ORM\Index(name="IX_field_types_2", columns={"field_type"})})
 * @ORM\Entity
 */
class FieldType
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="field_type", type="string", length=32, nullable=false)
     */
    private $fieldType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return FieldType
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set fieldType
     *
     * @param string $fieldType
     *
     * @return FieldType
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FieldType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string Type class name
     */
    public function getTypeClassName()
    {
        $snakeCaseName = $this->getFieldType();
        $camelCaseName = implode('', array_map('ucfirst', explode('_', $snakeCaseName)));

        return 'AppBundle\\Form\\Type\\Custom'.$camelCaseName.'Type';
    }
}
