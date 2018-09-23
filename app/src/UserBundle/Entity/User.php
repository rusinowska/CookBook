<?php
/**
 * User entity.
 */
namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Translation\Util\ArrayConverter;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fos_user"
 * )
 */
class User extends BaseUser
{
    /**
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
     * Recipes
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Recipe",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    protected $rates;

    /**
     * Ingredients
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Ingredient",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    protected $ingredients;

    /**
     * categories
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Category",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    protected $categories;

    /**
     * Photos
     *
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Entity\Photo",
     *     mappedBy="user",
     *     cascade={"persist", "remove"},
     *     orphanRemoval=true
     *     )
     */
    protected $photos;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}
