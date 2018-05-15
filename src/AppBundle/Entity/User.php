<?php


namespace AppBundle\Entity;


use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Faker\Provider\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="It looks like you already have an account!")
 */
class User implements UserInterface
{

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(groups={"Registration"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(name="reset_token", type="string", length=64, nullable=true)
     */
    private $resetToken;

    /**
     * @ORM\Column(name="reset_token_expires_at", type="integer", nullable=true)
     */
    private $resetTokenExpiresAt;

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        if(!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param \DateInterval $interval
     * @return string
     */
    public function generateResetToken(\DateInterval $interval): string
    {
        $now = new \DateTime();
        $this->resetToken = Uuid::uuid();
        $this->resetTokenExpiresAt = $now->add($interval)->getTimestamp();
        return $this->resetToken;
    }

    public function clearResetToken()
    {
        $this->resetToken = null;
        $this->resetTokenExpiresAt = null;
    }

    public function isResetTokenValid(string $token): bool
    {
        return
            $this->resetToken === $token &&
            $this->resetTokenExpiresAt !== null &&
            $this->resetTokenExpiresAt > time();
    }

    /**
     *
     * @param $password
     * @throws \DomainException
     */
    public function resetPassword($password)
    {
        if(empty($this->resetToken)) {
            throw new \DomainException('Password resetting is not requested.');
        }

        $this->setPlainPassword($password);
        $this->clearResetToken();

    }

    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

}