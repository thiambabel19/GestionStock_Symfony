<?php

namespace App\Entity;

use App\Entity\Roles;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 40)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 40)]
    private $nom;

    #[ORM\ManyToMany(targetEntity: Roles::class, inversedBy: 'users')]
    private $roles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Produit::class)]
    private $produits;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Entree::class)]
    private $entrees;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sortie::class)]
    private $sorties;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Categorie::class, orphanRemoval: true)]
    private $categories;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->entrees = new ArrayCollection();
        $this->sorties = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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
        return (string) $this->email;
    }
    
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = array();
        foreach($this->roles as $r){
            $roles[] = $r->getNomRole();
        }
      
        return array_unique($roles);
    }

    /**
     * Set the value of produits
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    /**
     * Set the value of produits
     *
     * @return  self
     */ 
    public function setProduits($produits)
    {
        $this->produits = $produits;

        return $this;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setUser($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUser() === $this) {
                $produit->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entree>
     */
    public function getEntrees(): Collection
    {
        return $this->entrees;
    }

    public function addEntree(Entree $entree): self
    {
        if (!$this->entrees->contains($entree)) {
            $this->entrees[] = $entree;
            $entree->setUser($this);
        }

        return $this;
    }

    public function removeEntree(Entree $entree): self
    {
        if ($this->entrees->removeElement($entree)) {
            // set the owning side to null (unless already changed)
            if ($entree->getUser() === $this) {
                $entree->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setUser($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getUser() === $this) {
                $sorty->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUser($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUser() === $this) {
                $category->setUser(null);
            }
        }

        return $this;
    }
}