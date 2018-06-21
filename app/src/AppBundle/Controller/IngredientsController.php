<?php
/**
 * Ingredients controller.
 */
namespace AppBundle\Controller;

use AppBundle\Repository\IngredientsRepository;
use AppBundle\Entity\Ingredient;
use AppBundle\Form\IngredientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IngredientsController.
 *
 * @Route("/ingredients")
 */
class IngredientsController extends Controller
{
    /**
     * Ingredients repository.
     *
     * @var \AppBundle\Repository\IngredientsRepository|null $ingredientsRepository
     */
    protected $ingredientsRepository = null;

    /**
     * IngredientsController constructor.
     *
     * @param \AppBundle\Repository\IngredientsRepository $ingredientsRepository Ingredients repository
     */
    public function __construct(IngredientsRepository $ingredientsRepository)
    {
        $this->ingredientsRepository = $ingredientsRepository;
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
        $ingredients = $this->ingredientsRepository->findAllPaginated($page);

        return $this->render(
            'ingredients/index.html.twig',
            ['ingredients' => $ingredients]
        );

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
        return $this->render(
            'ingredients/view.html.twig',
            ['ingredient' => $ingredient]
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
     *     name="ingredients_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
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
    }
}