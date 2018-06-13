<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Characteristic
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CharacteristicRepository")
 * @ORM\Table(name="characteristic")
 */
class Characteristic
{
    private const TYPE_STRING = 'string';
    private const TYPE_INTEGER = 'integer';
    private const TYPE_FLOAT = 'float';

    public function __construct()
    {
        $this->variants = new ArrayCollection();
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
     * @ORM\Column(name="type", type="string")
     */
    private $type;

    /**
     * @ORM\Column(name="required", type="boolean")
     */
    private $required;

    /**
     * @ORM\Column(name="default_value", type="string", nullable=true)
     */
    private $default;

    /**
     * @ORM\Column(name="variants", type="json")
     */
    private $variants;

    /**
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;


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
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * @param mixed $variants
     */
    public function setVariants($variants): void
    {
        $this->variants = $variants;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default): void
    {
        $this->default = $default;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * @param mixed $required
     */
    public function setRequired($required): void
    {
        $this->required = $required;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeName()
    {
        $types = self::typesList();
        return $types[$this->type];
    }

    /**
     * @param mixed $type
     * @throws \DomainException
     */
    public function setType($type): void
    {
        if(\in_array($type, $this->typesArray(), true)) {
            $this->type = $type;
            return;
        }
        throw new \DomainException('Characteristic type not found');
    }

    public function typesArray(): array
    {
        return [
            self::TYPE_FLOAT,
            self::TYPE_INTEGER,
            self::TYPE_STRING,
        ];
    }

    public static function typesList(): array
    {
        return [
            self::TYPE_INTEGER => 'Целое число',
            self::TYPE_FLOAT => 'Вещественное число',
            self::TYPE_STRING => 'Строка',
        ];
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }
}