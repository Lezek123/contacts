<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Contact
 *
 * @ORM\Table(name="contacts", indexes={@ORM\Index(name="IX_contacts_1", columns={"is_deleted"}), @ORM\Index(name="IX_contacts_2", columns={"firstname"}), @ORM\Index(name="IX_contacts_3", columns={"lastname"})})
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ContactRepository")
 */
class Contact
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
     * @ORM\Column(name="firstname", type="string", length=128, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=128)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=128, nullable=true)
     * Assert\Length(max=128)
     */
    private $lastname;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var \AppBundle\Entity\ContactDetail
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContactDetail", mappedBy="contact", orphanRemoval=true, cascade={"persist"}, indexBy="id")
     * @Assert\Valid()
     */
    private $details;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contact = new ArrayCollection();
        $this->details = new ArrayCollection();
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return Contact
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Contact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Contact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
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
     * Get details
     *
     * @return Collection
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Add detail
     *
     * @param \AppBundle\Entity\ContactDetail $detail
     *
     * @return Contact
     */
    public function addDetail(ContactDetail $detail)
    {
        $detail->setContact($this);
        $this->details->add($detail);

        return $this;
    }

    /**
     * Remove detail
     *
     * @param \AppBundle\Entity\ContactDetail $detail
     * @param bool                            $hard   Whether to delete from db or not
     */
    public function removeDetail(ContactDetail $detail, $hard = false)
    {
        $detail->setIsDeleted(true);
        if ($hard) {
            $this->details->removeElement($detail);
        }
    }

    /**
     * Get undeleted details
     *
     * @return ArrayCollection
     */
    public function getActiveDetails()
    {
        $result = new ArrayCollection();
        foreach ($this->details as $detail) {
            if (!$detail->getIsDeleted() and $detail->getFieldType()->getIsActive()) {
                $result->add($detail);
            }
        }

        return $result;
    }


    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }
}
