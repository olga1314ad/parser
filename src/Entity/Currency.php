<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *  @ApiResource(
 *      normalizationContext={"groups"={"read"}},
 *      denormalizationContext={"groups"={"write"}}
 *     )
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $rate;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="currency")
     */
    private $store;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"read"})
     */
    private $name;

    public function __construct()
    {
        $this->store = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getStore(): Collection
    {
        return $this->store;
    }

    public function addStore(Offer $store): self
    {
        if (!$this->store->contains($store)) {
            $this->store[] = $store;
            $store->setCurrency($this);
        }

        return $this;
    }

    public function removeStore(Offer $store): self
    {
        if ($this->store->removeElement($store)) {
            // set the owning side to null (unless already changed)
            if ($store->getCurrency() === $this) {
                $store->setCurrency(null);
            }
        }

        return $this;
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

}
