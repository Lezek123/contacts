<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactDetail
 *
 * @ORM\Table(name="contact_details", indexes={@ORM\Index(name="IX_contact_details_1", columns={"is_deleted"}), @ORM\Index(name="IX_contact_details_2", columns={"is_deleted", "value"}), @ORM\Index(name="FK_contact_details_1", columns={"contact_id"}), @ORM\Index(name="FK_contact_details_2", columns={"field_type_id"})})
 * @ORM\Entity
 */
class ContactDetail
{

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false)
     * @Assert\Type("boolean")
     */
    private $isDeleted = false;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     * @Assert\Length(max=255, maxMessage="long.detail")
     * @Assert\NotBlank(message="blank.detail")
     */
    private $value;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\FieldType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FieldType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_type_id", referencedColumnName="id")
     * })
     * @Assert\Valid()
     */
    private $fieldType;

    /**
     * @var \AppBundle\Entity\Contact
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="details")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     * })
     */
    private $contact;

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return ContactDetail
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return ContactDetail
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
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
     * Set fieldType
     *
     * @param \AppBundle\Entity\FieldType $fieldType
     *
     * @return ContactDetail
     */
    public function setFieldType(FieldType $fieldType = null)
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    /**
     * Get fieldType
     *
     * @return \AppBundle\Entity\FieldType
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return ContactDetail
     */
    public function setContact(Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getFieldType()->getFieldType();
    }

    /**
     * @Assert\IsTrue(message = "This FieldType is not active anymore")
     * @return boolean
     */
    public function isFieldActive()
    {
        return $this->getFieldType()->getIsActive();
    }
}
