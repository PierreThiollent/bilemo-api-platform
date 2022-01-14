<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Validator;

#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'delete'],
    denormalizationContext: ['groups' => ['write']],
    normalizationContext: ['groups' => ['read']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: 'Un utilisateur avec cet email existe déjà')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read', 'write'])]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['read', 'write'])]
    #[Validator\NotBlank(message: 'Le champ email ne doit pas être vide.')]
    #[Validator\Email(message: 'Le champ email doit être un email valide.')]
    private string $email;

    #[ORM\Column(type: 'string')]
    #[Groups(['write'])]
    #[Validator\NotBlank(message: 'Le mot de passe ne doit pas être vide.')]
    #[Validator\Length(
        max: 255,
        maxMessage: 'Le mot de passe ne doit pas faire plus de {{ limit }} caractères.'
    )]
    private string $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'write'])]
    #[Validator\NotBlank(message: 'Le nom ne doit pas être vide.')]
    #[Validator\Length(
        min: 2,
        max: 255,
        minMessage: 'Le nom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le nom ne doit pas faire plus de {{ limit }} caractères.'
    )]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read', 'write'])]
    #[Validator\NotBlank(message: 'Le prénom ne doit pas être vide.')]
    #[Validator\Length(
        min: 2,
        max: 255,
        minMessage: 'Le prénom doit faire au moins {{ limit }} caractères.',
        maxMessage: 'Le prénom ne doit pas faire plus de {{ limit }} caractères.'
    )]
    private string $lastname;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write'])]
    private Client $client;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client|UserInterface $client): self
    {
        $this->client = $client;

        return $this;
    }
}
