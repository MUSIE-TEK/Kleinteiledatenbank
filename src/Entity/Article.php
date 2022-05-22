<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $artnumber;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\ManyToOne(targetEntity: Supplier::class, inversedBy: 'articles')]
    private $supplier;

    #[ORM\Column(type: 'integer')]
    private $minimumstock;

    #[ORM\Column(type: 'integer')]
    private $orderid;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $approval;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $imac;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $comment;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $compatibilities;

    #[ORM\Column(type: 'integer')]
    private $minimumorderamount;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $archive;

    #[ORM\Column(type: 'datetime')]
    private $archivedate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $inventory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtnumber(): ?int
    {
        return $this->artnumber;
    }

    public function setArtnumber(int $artnumber): self
    {
        $this->artnumber = $artnumber;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getMinimumstock(): ?int
    {
        return $this->minimumstock;
    }

    public function setMinimumstock(int $minimumstock): self
    {
        $this->minimumstock = $minimumstock;

        return $this;
    }

    public function getOrderid(): ?int
    {
        return $this->orderid;
    }

    public function setOrderid(int $orderid): self
    {
        $this->orderid = $orderid;

        return $this;
    }

    public function getApproval(): ?int
    {
        return $this->approval;
    }

    public function setApproval(?int $approval): self
    {
        $this->approval = $approval;

        return $this;
    }

    public function getImac(): ?int
    {
        return $this->imac;
    }

    public function setImac(?int $imac): self
    {
        $this->imac = $imac;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCompatibilities(): ?string
    {
        return $this->compatibilities;
    }

    public function setCompatibilities(?string $compatibilities): self
    {
        $this->compatibilities = $compatibilities;

        return $this;
    }

    public function getMinimumorderamount(): ?int
    {
        return $this->minimumorderamount;
    }

    public function setMinimumorderamount(int $minimumorderamount): self
    {
        $this->minimumorderamount = $minimumorderamount;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getArchivedate(): ?\DateTimeInterface
    {
        return $this->archivedate;
    }

    public function setArchivedate(\DateTimeInterface $archivedate): self
    {
        $this->archivedate = $archivedate;

        return $this;
    }

    public function getInventory(): ?string
    {
        return $this->inventory;
    }

    public function setInventory(?string $inventory): self
    {
        $this->inventory = $inventory;

        return $this;
    }
}
