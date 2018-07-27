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
        $this->photos = new ArrayCollection();
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
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Value",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $values;

    /**
     * @var Photo[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Photo",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    private $photos;

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
     * @param Value $value
     */
    public function addValue(Value $value)
    {
        if(!$this->values->contains($value)) {
            $this->values->add($value);
        }
    }

    public function removeValue(Value $value)
    {
        if($this->values->contains($value)) {
            $this->values->removeElement($value);
        }
    }

    public function removeAllValues(): void
    {
        $this->values->clear();
    }

    /**
     * @return Value[]|ArrayCollection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param Characteristic $characteristic
     * @return Value|null
     */
    public function getValueByCharacteristic(Characteristic $characteristic): ?Value
    {
        foreach($this->values as $value) {
            if($value->isForCharacteristic($characteristic->getId())) {
                return $value;
            }
        }
        return null;
    }

    /**
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo): void
    {
        if(!$this->photos->contains($photo)) {
            $this->photos->add($photo);
        }
    }

    /**
     * Удаление фото
     * @param integer $photoId
     */
    public function removePhoto($photoId): void
    {
        foreach($this->photos as $photo) {
            if($photo->getId() === (int) $photoId) {
                $this->photos->removeElement($photo);
                $photo->setProduct(null);
                return;
            }
        }
        throw new \DomainException('Photo not found');
    }

    /**
     * Удаление всех фото
     */
    public function removeAllPhotos(): void
    {
        $this->photos->clear();
    }

    /**
     * Перемещение фотографии вверх на одну позицию
     * @param integer $id
     */
    public function movePhotoUp($id): void
    {
        foreach($this->photos as $i => $photo) {
            if($photo->getId() === (int) $id) {
                if ($prev = $this->photos[$i - 1] ?? null) {
                    $this->photos[$i - 1] = $photo;
                    $this->photos[$i] = $prev;
                    $this->updateSortPhotos();
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    /**
     * Перемещение фотографии вниз на 1 позицию
     * @param integer $id
     */
    public function movePhotoDown($id): void
    {
        foreach($this->photos as $i => $photo) {
            if($photo->getId() === (int) $id) {
                if ($next = $this->photos[$i + 1] ?? null) {
                    $this->photos[$i] = $next;
                    $this->photos[$i + 1] = $photo;
                    $this->updateSortPhotos();
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    /**
     * Обновление сортировки фото
     */
    public function updateSortPhotos(): void
    {
        foreach($this->photos as $i => $photo) {
            $this->photos[$i]->setSort($i);
        }
    }

    /**
     * Получить максимальный существующий номер сортировки
     * @return int
     */
    public function getMaxSortValue(): int
    {
        $maxSort = 0;
        foreach($this->photos as $photo) {
            $maxSort = ($photo->getSort() > $maxSort) ? $photo->getSort() : $maxSort;
        }
        return $maxSort;
    }


    /**
     * @return Photo[]|ArrayCollection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}