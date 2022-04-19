<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 */
class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=150)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class, inversedBy="store")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="boolean")
     */
    private $store;

    /**
     * @ORM\Column(type="boolean")
     */
    private $pickup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $delivery;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryOptions::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery_option;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $typePrefix;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $vendor_code;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $barcode;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendor;

    /**
     * @ORM\ManyToMany(targetEntity=SalesNotes::class, inversedBy="offers")
     *  @ORM\JoinTable(name = "offer_to_notes")
     */
    private $sales_notes;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="offer", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity=Params::class, mappedBy="offer", orphanRemoval=true)
     */
    private $params;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    public function __construct()
    {
        $this->sales_notes = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->params = new ArrayCollection();
    }

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return Collection<int, SalesNotes>
     */
    public function getSalesNotes(): Collection
    {
        return $this->sales_notes;
    }

    public function addSalesNote(SalesNotes $salesNote): self
    {
        if (!$this->sales_notes->contains($salesNote)) {
            $this->sales_notes[] = $salesNote;
        }

        return $this;
    }

    public function removeSalesNote(SalesNotes $salesNote): self
    {
        $this->sales_notes->removeElement($salesNote);

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setOffer($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getOffer() === $this) {
                $picture->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Params>
     */
    public function getParams(): Collection
    {
        return $this->params;
    }

    public function addParam(Params $param): self
    {
        if (!$this->params->contains($param)) {
            $this->params[] = $param;
            $param->setOffer($this);
        }

        return $this;
    }

    public function removeParam(Params $param): self
    {
        if ($this->params->removeElement($param)) {
            // set the owning side to null (unless already changed)
            if ($param->getOffer() === $this) {
                $param->setOffer(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStore(): ?bool
    {
        return $this->store;
    }

    public function setStore(bool $store): self
    {
        $this->store = $store;

        return $this;
    }

    public function getPickup(): ?bool
    {
        return $this->pickup;
    }

    public function setPickup(bool $pickup): self
    {
        $this->pickup = $pickup;

        return $this;
    }

    public function getDelivery(): ?bool
    {
        return $this->delivery;
    }

    public function setDelivery(bool $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getTypePrefix(): ?string
    {
        return $this->typePrefix;
    }

    public function setTypePrefix(string $typePrefix): self
    {
        $this->typePrefix = $typePrefix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVendorCode(): ?string
    {
        return $this->vendor_code;
    }

    public function setVendorCode(string $vendor_code): self
    {
        $this->vendor_code = $vendor_code;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getDeliveryOption(): ?DeliveryOptions
    {
        return $this->delivery_option;
    }

    public function setDeliveryOption(?DeliveryOptions $delivery_option): self
    {
        $this->delivery_option = $delivery_option;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }


}
