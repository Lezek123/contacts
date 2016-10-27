<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity("email", groups = {"register", "edit"})
 * @UniqueEntity("username", groups = {"register", "edit"})
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank(groups = {"register", "edit"})
     * @Assert\Length(min=3, max=25, groups = {"register", "edit"})
     * @Assert\Regex("/^[a-zA-Z0-9]*$/", message="invalid.username", groups = {"register", "edit"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min=8, max=25, groups = {"register"})
     * @Assert\Regex("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[^A-Za-z0-9]).*$/", message="invalid.password", groups = {"register"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\Email(groups = {"register"})
     * @Assert\Length(max=60, groups = {"register"})
     *
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="array")
     * @Assert\All(
     *     constraints = { @Assert\Choice({"ROLE_USER", "ROLE_ADMIN"}) },
     *     groups = {"edit"}
     * );
     */
    private $roles = array('ROLE_USER');

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $roles
     * @return User $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Ease Credentials
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
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
     * @Assert\IsTrue(groups = {"edit"}, message="user.must.have.user.role");
     * @return bool
     */
    public function hasUserRole()
    {
        return in_array('ROLE_USER', $this->roles, true);
    }
}
