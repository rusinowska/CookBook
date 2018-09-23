<?php
/**
 * Recipes controller.
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\Photo;
use AppBundle\Form\RecipeType;
use AppBundle\Repository\RecipesRepository;
use AppBundle\Repository\IngredientsRepository;
use AppBundle\Repository\CategoriesRepository;
use AppBundle\Repository\PhotosRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RecipesController.
 *
 * @Route("/recipes")
 */
class RecipesController extends Controller
{
    /**
     * Authentication Checker.
     *
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface|null $security
     */
    protected $authorizationChecker = null;

    /**
     * Token storage.
     *
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface|null $tokenStorage
     */
    protected $tokenStorage = null;


    /**
     * Recipes repository.
     *
     * @var \AppBundle\Repository\RecipesRepository|null $recipesRepository
     */
    protected $recipesRepository = null;

    /**
     * Ingredients repository.
     *
     * @var \AppBundle\Repository\IngredientsRepository|null $ingredientsRepository
     */
    protected $ingredientsRepository = null;

    /**
     * Categories repository.
     *
     * @var \AppBundle\Repository\CategoriesRepository|null $categoriesRepository
     */
    protected $categoriesRepository = null;

    /**
     * Photos repository.
     *
     * @var \AppBundle\Repository\PhotosRepository|null $photosRepository
     */
    protected $photosRepository = null;


    /**
     * RecipesController constructor.
     *
     * @param \AppBundle\Repository\RecipesRepository                                             $recipesRepository     Recipes repository
     * @param \AppBundle\Repository\RecipesRepository                                             $ingredientsRepository Ingredients repository
     * @param \AppBundle\Repository\RecipesRepository                                             $categoriesRepository  Categories repository
     * @param \AppBundle\Repository\RecipesRepository                                             $photosRepository      Photos repository
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface        $authorizationChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(RecipesRepository $recipesRepository, IngredientsRepository $ingredientsRepository, CategoriesRepository $categoriesRepository, PhotosRepository $photosRepository, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->recipesRepository = $recipesRepository;
        $this->ingredientsRepository = $ingredientsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->photosRepository = $photosRepository;
        $this->authorizationChecker = $authorizationChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Index action.
     *
     * @param integer $page Current page number
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/",
     *     defaults={"page": 1},
     *     name="recipes_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="recipes_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $recipes = $this->recipesRepository->findAllPaginated($page);

            $ingredients = $this->ingredientsRepository->findAllPaginated($page);
            $categories = $this->categoriesRepository->findAllPaginated($page);

            return $this->render(
                'recipes/index.html.twig',
                [
                    'recipes' => $recipes,
                    'ingredients' => $ingredients,
                    'categories' => $categories,
                    ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');

            return $response;
        }
    }

    /**
     * View action.
     *
     * @param Recipe $recipe Recipe entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipes_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Recipe $recipe)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $ingredients = $recipe->getIngredients();
            $category = $recipe->getCategory();
            $photos = $recipe->getPhotos();

            return $this->render(
                'recipes/view.html.twig',
                [
                    'recipe' => $recipe,
                    'category' => $category,
                    'ingredients' => $ingredients,
                    'photos' => $photos,
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');

            return $response;
        }
    }

    /**
     * Show results by user.
     *
     * @param integer $page Current page number
     * @param integer $user Searched user id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/user/{user}",
     *     defaults={"page": 1},
     *     name="recipes_user",
     * )
     *
     * @Route(
     *     "/user/{user}/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     defaults={"page": 1},
     *     name="recipes_user_results",
     * )
     *
     * @Method({"GET"})
     */
    public function viewbyuserAction($page, $user = null)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $searched = $this->recipesRepository->findBy(['user' => $user]);

            if (empty($searched)) {
                $this->addFlash('danger', 'message.search_result_empty');
                $recipes = $this->recipesRepository->findAllPaginated($page);
            } else {
                $recipes = $this->recipesRepository->findAllPaginatedByUser($user, $page);
            }

            return $this->render(
                'recipes/index.html.twig',
                ['recipes' => $recipes]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }

    /**
     * Show results by category.
     *
     * @param integer $page     Current page number
     * @param integer $category Searched category id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/category/{category}",
     *     defaults={"page": 1},
     *     name="recipes_category",
     * )
     *
     * @Route(
     *     "/category/{category}/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     defaults={"page": 1},
     *     name="recipes_category_results",
     * )
     *
     * @Method({"GET"})
     */
    public function viewbycategoryAction($page, $category = null)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $searched = $this->recipesRepository->findBy(['category' => $category]);

            if (empty($searched)) {
                $this->addFlash('danger', 'message.search_result_empty');
                $recipes = $this->recipesRepository->findAllPaginated($page);
            } else {
                $recipes = $this->recipesRepository->findAllPaginatedByCategory($category, $page);
            }

            return $this->render(
                'recipes/index.html.twig',
                ['recipes' => $recipes]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }

    /**
     * Show results by ingredient.
     *
     * @param integer $page       Current page number
     * @param integer $ingredient Searched ingredient id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/ingredient/{ingredient}",
     *     defaults={"page": 1},
     *     name="recipes_ingredient",
     * )
     *
     * @Route(
     *     "/ingredient/{ingredient}/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     defaults={"page": 1},
     *     name="recipes_ingredient_results",
     * )
     *
     * @Method({"GET"})
     */
    public function viewbyingredientAction($page, $ingredient = null)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $recipes = $this->recipesRepository->findAllPaginatedByIngredient($ingredient, $page);
            $ingredientelement = $this->ingredientsRepository->findOneById($ingredient);

            $filter = "ingredient";

            return $this->render(
                'recipes/index.html.twig',
                ['recipes' => $recipes, 'ingredient' => $ingredientelement, 'filter' => $filter]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }

    /**
     * Add action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/add",
     *     name="recipes_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->tokenStorage->getToken()->getUser();


            $recipe = new Recipe();

            $photo = new Photo();
            $recipe->addPhoto($photo);
            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $recipe->setUser($user);
                $this->recipesRepository->save($recipe);
                $this->addFlash('success', 'message.created_successfully');

                return $this->redirectToRoute('recipes_index');
            }

            return $this->render(
                'recipes/add.html.twig',
                [
                    'recipe' => $recipe,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Recipe                  $recipe  Recipe entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipes_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Recipe $recipe)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->recipesRepository->save($recipe);
                $this->addFlash('success', 'message.modified_successfully');


                return $this->redirectToRoute('recipes_view', ['id' => $recipe->getId()]);
            }

            return $this->render(
                'recipes/edit.html.twig',
                [
                    'recipe' => $recipe,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Recipe                  $recipe  Recipe entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipes_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Recipe $recipe)
    {
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $form = $this->createForm(FormType::class, $recipe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->recipesRepository->delete($recipe);
                $this->addFlash('success', 'message.deleted_successfully');

                return $this->redirectToRoute('recipes_index');
            }

            return $this->render(
                'recipes/delete.html.twig',
                [
                    'recipe' => $recipe,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }
}
