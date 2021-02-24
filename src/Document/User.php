<?php

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ODM\Document
 */
class User implements UserInterface
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     */
    private $username;

    /**
     * @ODM\Field(type="string")
     */
    private $password;

    /**
     * @ODM\Field(type="string", nullable=true)
     */
    private $token;

    /**
     * @ODM\ReferenceMany(targetDocument=VOD::class, cascade="all")
     */
    private $favs;

    public function __construct()
    {
        $this->favs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|VOD[]
     */
    public function getFavs(): Collection
    {
        return $this->favs;
    }

    public function addFav(VOD $fav): self
    {
        if (!$this->favs->contains($fav)) {
            $this->favs[] = $fav;
        }

        return $this;
    }

    public function removeFav(VOD $fav): self
    {
        $this->favs->removeElement($fav);

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {}
}
