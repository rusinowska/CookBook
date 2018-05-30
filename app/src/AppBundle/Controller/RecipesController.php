<?php
/**
 * Recipes controller.
 */
namespace AppBundle\Controller;

use AppBundle\Repository\RecipesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
     * RecipesController constructor.
     *
     * @param \AppBundle\Repository\RecipesRepository $recipesRepository Recipes repository
     */
    public function __construct(RecipesRepository $recipesRepository)
    {
        $this->recipesRepository = $recipesRepository;
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

        return $this->render(
            'recipes/index.html.twig',
            ['recipes' => $recipes]
        );

    }
}