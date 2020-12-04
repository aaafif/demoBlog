<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *      fields = {"email"},
 *      message="Un compte est déjà existant à cette adresse Email."
 * )
 * @UniqueEntity(
 *      fields = {"username"},
 *      message="Ce nom est déjà existant, veuillez en saisir un nouveau."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez renseigner un Email.")
     * @Assert\Email(message="Veuillez saisir une adresse Email valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veillez saisir un nom d'utilisateur.")
     * @Assert\Length(
     *          min="2",
     *          minMessage="Votre nom d'utilisateur doit faire minimum 2 caractères."
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min="2",
     *          minMessage="Votre mot de passe doit faire minimum 2 caractères."
     * )
     * @Assert\EqualTo(
     *          propertyPath="confirm_password",
     *          message="Les mots de passe ne correspondent pas.",
     *          groups={"registration"}
     * )
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *          propertyPath="password",
     *          message="Les mots de passe ne correspondent pas.",
     *          groups={"registration"}
     * )
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getSalt()
    {
        
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    
}
