<?php

namespace AppBundle\Controller;


use Doctrine\ORM\EntityManager;
use AppBundle\Repository\RecipesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DefaultController extends Controller
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
     * @param \AppBundle\Repository\RecipesRepository $recipesRepository
     */
    public function __construct(RecipesRepository $recipesRepository)
    {
        $this->recipesRepository = $recipesRepository;
    }
//    /**
//     * @Route("/", name="homepage")
//     */
//    public function indexAction(Request $request)
//    {
//        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
//        ]);
//    }

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
         *     name="home_index",
         * )
         * @Route(
         *     "/page/{page}/",
         *     requirements={"page": "[1-9]\d*"},
         *     name="home_index_paginated",
         * )
         *
         * @Method({"GET"})
         */
        public function indexAction($page)
        {

            $recipes = $this->recipesRepository->findAllPaginated($page);

            return $this->render(
                'home/home.html.twig',
                ['recipes' => $recipes]
            );
        }
}
