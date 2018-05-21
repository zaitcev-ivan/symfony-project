<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Category
 * @package AppBundle\Entity
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    private const ROOT_CATEGORY = 'root';

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\TreeLeft()
     * @ORM\Column(name="lft", type="integer")
     */
    private $left;

    /**
     * @Gedmo\TreeRight()
     * @ORM\Column(name="rgt", type="integer")
     */
    private $right;

    /**
     * @Gedmo\TreeLevel()
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @Gedmo\TreeRoot()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @Gedmo\TreeParent()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Category", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;

    /**
     * @var Product[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="category")
     */
    private $products;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @throws \DomainException
     */
    public function setName($name): void
    {
        if($this->isEqualRootCategory($name)) {
            throw new \DomainException('Название \'root\' зарезервировано для корневой категории');
        }
        $this->name = $name;
    }

    public function setRootName(): void
    {
        $this->name = self::ROOT_CATEGORY;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent(Category $parent = null): void
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param mixed $left
     */
    public function setLeft($left): void
    {
        $this->left = $left;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $right
     */
    public function setRight($right): void
    {
        $this->right = $right;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level): void
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function isEqualRootCategory($name): bool
    {
        return self::ROOT_CATEGORY === $name;
    }

    public static function getRootCategoryName(): string
    {
        return self::ROOT_CATEGORY;
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }
}