<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
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

}
