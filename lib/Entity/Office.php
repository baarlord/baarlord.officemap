<?php

namespace Baarlord\OfficeMap\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bo_office")
 */
class Office
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $floor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="integer")
     */
    private $sort;

    /**
     * @param $id
     * @param $name
     * @param $active
     * @param $code
     * @param $floor
     * @param $file
     * @param $sort
     */
    public function __construct(
        $id,
        $name,
        $active,
        $code,
        $floor,
        $file,
        $sort
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
        $this->code = $code;
        $this->floor = $floor;
        $this->file = $file;
        $this->sort = $sort;
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

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(string $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(string $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getFile(): ?int
    {
        return $this->file;
    }

    public function setFile(?int $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
