<?php
/**
 * Ingredient entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Ingredient.
 *
 * @ORM\Table(
 *     name="ingredients"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\IngredientsRepository"
 * )
 * @UniqueEntity(
 *     fields={"name"}
 * )
 */
class Ingredient
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * Recipes.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $recipes
     *
     * @ORM\ManyToMany(
     *     targetEntity="Recipe",
     *     mappedBy="ingredients",
     * )
     */
    protected $recipes;

    /**
     * Primary key.
     *
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name.
     *
     * @var string $name
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="128",
     * )
     */
    protected $name;

    /**
     * User
     *
     * @ORM\ManyToOne(
     *     targetEntity="UserBundle\Entity\User",
     *     inversedBy="ingredients"
     * )
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    protected $user;

//    /**
//     * Quantity.
//     *
//     * @var string $quantity
//     *
//     * @ORM\Column(
//     *     name="quantity",
//     *     type="string",
//     *     length=128,
//     *     nullable=false,
//     * )
//     *
//     * @Assert\NotBlank
//     * @Assert\Length(
//     *     min="3",
//     *     max="128",
//     * )
//     */
//    protected $quantity;
//
//    /**
//     * Unit.
//     *
//     * @var string $unit
//     *
//     * @ORM\Column(
//     *     name="unit",
//     *     type="string",
//     *     length=128,
//     *     nullable=false,
//     * )
//     *
//     * @Assert\NotBlank
//     * @Assert\Length(
//     *     min="3",
//     *     max="128",
//     * )
//     */
//    protected $unit;

//    /**
//     * Set quantity
//     *
//     * @param string $quantity
//     *
//     * @return Ingredient
//     */
//    public function setQuantity($quantity)
//    {
//        $this->quantity = $quantity;
//
//        return $this;
//    }

//    /**
//     * Get quantity.
//     *
//     * @return string
//     */
//    public function getQuantity()
//    {
//        return $this->quantity;
//    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Ingredient
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add recipe
     *
     * @param \AppBundle\Entity\Recipe $recipe
     *
     * @return Ingredient
     */
    public function addRecipe(\AppBundle\Entity\Recipe $recipe)
    {
        $this->recipes[] = $recipe;

        return $this;
    }

    /**
     * Remove recipe
     *
     * @param \AppBundle\Entity\Recipe $recipe
     */
    public function removeRecipe(\AppBundle\Entity\Recipe $recipe)
    {
        $this->recipes->removeElement($recipe);
    }

    /**
     * Get recipes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Ingredient
     */
    public function setUser(\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
