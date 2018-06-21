<?php
/**
 * Recipe entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Recipe.
 *
 * @ORM\Table(
 *     name="recipes"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\RecipesRepository"
 * )
 * @UniqueEntity(
 *     fields={"title"}
 * )
 */
class Recipe
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

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
     * Title.
     *
     * @var string $title
     *
     * @ORM\Column(
     *     name="title",
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
    protected $title;

    /**
     * Description.
     *
     * @var string $description
     *
     * @ORM\Column(
     *     name="description",
     *     type="text",
     *     length=500,
     *     nullable=false,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="10000",
     * )
     */
    protected $description;

    /**
     * Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="Category",
     *     inversedBy="recipes"
     * )
     *
     * @ORM\JoinColumn(
     *     name="category_id",
     *     referencedColumnName="id",
     *     onDelete="CASCADE")
     */
    private $category;

    /**
     * Ingredients.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $ingredients
     *
     * @ORM\ManyToMany(
     *     targetEntity="Ingredient",
     *     inversedBy="recipes",
     * )
     * @ORM\JoinTable(
     *     name="recipes_ingredients"
     * )
     */
    protected $ingredients;

    /**
     * Photo
     *
     * @ORM\OneToMany(
     *     targetEntity="Photo",
     *     mappedBy="recipes",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    protected $photos;


//    /**
//     * Photo.
//     *
//     * @var \Doctrine\Common\Collections\ArrayCollection $photos
//     *
//     * @ORM\ManyToMany(
//     *     targetEntity="Photo",
//     *     inversedBy="photo",
//     *     mappedBy="recipe",
//     * )
//     * @ORM\JoinTable(
//     *     name="recipes_photos"
//     * )
//     */





    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Recipe
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Recipe
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Recipe
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add ingredient
     *
     * @param \AppBundle\Entity\Ingredient $ingredient
     *
     * @return Recipe
     */
    public function addIngredient(\AppBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \AppBundle\Entity\Ingredient $ingredient
     */
    public function removeIngredient(\AppBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Add photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return Recipe
     */
    public function addPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}
