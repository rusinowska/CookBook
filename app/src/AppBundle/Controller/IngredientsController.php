<?php
/**
 * Ingredients controller.
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
use AppBundle\Form\IngredientType;
use AppBundle\Repository\IngredientsRepository;
use AppBundle\Repository\RecipesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class IngredientsController.
 *
 * @Route("/ingredients")
 */
class IngredientsController extends Controller
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
     * Ingredients repository.
     *
     * @var \AppBundle\Repository\IngredientsRepository|null $ingredientsRepository
     */
    protected $ingredientsRepository = null;

    /**
     * Recipes repository.
     *
     * @var \AppBundle\Repository\RecipesRepository|null $recipesRepository
     */
    protected $recipesRepository = null;

    /**
     * IngredientsController constructor.
     *
     * @param \AppBundle\Repository\IngredientsRepository $ingredientsRepository Ingredients repository
     * @param \AppBundle\Repository\RecipesRepository $recipesRepository Recipes repository
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(IngredientsRepository $ingredientsRepository, RecipesRepository $recipesRepository, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->ingredientsRepository = $ingredientsRepository;
        $this->recipesRepository = $recipesRepository;
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
     *     name="ingredients_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="ingredients_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $ingredients = $this->ingredientsRepository->findAllPaginated($page);

            return $this->render(
                'ingredients/index.html.twig',
                ['ingredients' => $ingredients,]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');
            return $response;
        }
    }
    /**
     * View action.
     *
     * @param Ingredient $ingredient Ingredient entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="ingredients_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Ingredient $ingredient)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $recipes = $ingredient->getRecipes();

            return $this->render(
                'ingredients/view.html.twig',
                [
                    'ingredient' => $ingredient,
                    'recipes' => $recipes,
                    ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');
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
     *     name="ingredients_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $ingredient = new Ingredient();
            $form = $this->createForm(IngredientType::class, $ingredient);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->ingredientsRepository->save($ingredient);
                $this->addFlash('success', 'message.created_successfully');

                return $this->redirectToRoute('ingredients_index');
            }

            return $this->render(
                'ingredients/add.html.twig',
                [
                    'ingredient' => $ingredient,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Ingredient                     $ingredient     Ingredient entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="ingredients_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ingredient $ingredient)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form = $this->createForm(IngredientType::class, $ingredient);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->ingredientsRepository->save($ingredient);
                $this->addFlash('success', 'message.modified_successfully');

                return $this->redirectToRoute('ingredients_index');
            }

            return $this->render(
                'ingredients/edit.html.twig',
                [
                    'ingredient' => $ingredient,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Ingredient                     $ingredient     Ingredient entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="ingredients_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Ingredient $ingredient)
    {
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $form = $this->createForm(FormType::class, $ingredient); //nie Ingredient ???
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->ingredientsRepository->delete($ingredient);
                $this->addFlash('success', 'message.deleted_successfully');

                return $this->redirectToRoute('ingredients_index');
            }

            return $this->render(
                'ingredients/delete.html.twig',
                [
                    'ingredient' => $ingredient,
                    'form' => $form->createView(),
                ]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }

    }
}