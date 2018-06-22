<?php
/**
 * Recipes controller.
 */
namespace AppBundle\Controller;


use AppBundle\Entity\Recipe;
use AppBundle\Entity\Photo;
use AppBundle\Form\RecipeType;
use AppBundle\Form\ResultType;
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

/**
 * Class RecipesController.
 *
 * @Route("/recipes")
 */
class RecipesController extends Controller
{
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
     * @param \AppBundle\Repository\RecipesRepository $recipesRepository Recipes repository
     * @param \AppBundle\Repository\RecipesRepository $ingredientsRepository Ingredients repository
     * @param \AppBundle\Repository\RecipesRepository $categoriesRepository Categories repository
     * @param \AppBundle\Repository\RecipesRepository $photosRepository Photos repository
     */
    public function __construct(RecipesRepository $recipesRepository, IngredientsRepository $ingredientsRepository, CategoriesRepository $categoriesRepository, PhotosRepository $photosRepository)
    {
        $this->recipesRepository = $recipesRepository;
        $this->ingredientsRepository = $ingredientsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->photosRepository = $photosRepository;
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
        $recipes = $this->recipesRepository->findAllPaginated($page);

        //print_r($recipes);

        return $this->render(
            'recipes/index.html.twig',
            ['recipes' => $recipes]
        );
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
        $recipe = new Recipe();

        $photo = new Photo();
        $recipe->addPhoto($photo);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Recipe                     $recipe     Recipe entity
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
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipesRepository->save($recipe);
            $this->addFlash('success', 'message.modified_successfully');

            return $this->redirectToRoute('recipes_index');
        }

        return $this->render(
            'recipes/edit.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Recipe                     $recipe     Recipe entity
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
        $form = $this->createForm(FormType::class, $recipe); //nie Recipe ???
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
    }

    /**
     * Search action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Recipe $recipe Recipe entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/search",
     *     name="recipes_search",
     * )
     * @Method({"GET", "POST"})
     */
    public function searchAction(Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm(ResultType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $recipe = $this->recipesRepository->findOneBy(['ingredients' => $data->getIngredients()]);

            if (is_null($recipe)) {
                $this->addFlash('danger', 'message.not_found');

                return $this->redirectToRoute(
                    'recipes_index'
                );
            }

            return $this->redirectToRoute(
                'recipes_view',
                [
                    'id' => $recipe->getId(),
                ]
            );


        }

        return $this->render(
            'recipes/search.html.twig',
            [
                'recipe' => $recipe,
                'form' => $form->createView(),
            ]
        );
    }
}