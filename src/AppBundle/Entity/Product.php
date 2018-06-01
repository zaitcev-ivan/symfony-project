<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 */
class Product
{
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->assignmentsCategory = new ArrayCollection();
        $this->values = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinTable(name="category_assignments",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $assignmentsCategory;

    /**
     * @var Brand
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Brand", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $brand;

    /**
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var Value[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Value", mappedBy="product")
     */
    private $values;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     */
    public function setBrand(Brand $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ArrayCollection|Category[]
     */
    public function getAssignmentsCategory()
    {
        return $this->assignmentsCategory;
    }

    /**
     * @param Category $category
     */
    public function assignCategory(Category $category): void
    {
        if (!$this->assignmentsCategory->contains($category)) {
            $this->assignmentsCategory->add($category);
        }
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category): void
    {
        if ($this->assignmentsCategory->contains($category)) {
            $this->assignmentsCategory->remove($category);
        }
    }

    public function removeAllCategories(): void
    {
        $this->assignmentsCategory->clear();
    }

    /**
     * @param Characteristic $characteristic
     * @param $value
     */
    public function setValue(Characteristic $characteristic, $value): void
    {
        foreach($this->values as $i => $oneValue) {
            if($oneValue->isForCharacteristic($characteristic->getId())) {
                $this->values[$i]->setValue($value);
                return;
            }
        }
        $newValue = new Value($characteristic, $value);
        $this->values->add($newValue);
    }

    /**
     * @param Characteristic $characteristic
     * @return Value
     */
    public function getValue(Characteristic $characteristic): Value
    {
        foreach($this->values as $oneValue) {
            if($oneValue->isForCharacteristic($characteristic->getId())) {
                return $oneValue;
            }
        }

        return new Value($characteristic);
    }
}