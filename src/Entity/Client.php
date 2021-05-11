<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class) 
 * @UniqueEntity(
 * fields={"name"},
 * message="Un autre client possède déjà ce nom, merci de choisir un nom différent!"
 * )
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href=@Hateoas\Route(
 *          "clients_show",
 *          parameters={"id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "create",
 *      href=@Hateoas\Route(
 *          "clients_add",
 *          absolute = true
 *      )
 * )
 * 
 * @Hateoas\Relation(
 *      "update",
 *      href=@Hateoas\Route(
 *          "clients_update",
 *          parameters={"id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * 
 * 
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"listFull"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du client est obligatoire !")
     * @Assert\Length(min=2,minMessage="Le nom du client doit avoir aumoins {{ limit }} caractères !")
     * @Serializer\Groups({"listFull"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client")
     * @Serializer\Groups({"listFull"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }
/*
    public function jsonSerialize(): array
    {
       return [
           'id' => $this->getId(),
           'email' => $this->getName(),
       ];
    } */
}
