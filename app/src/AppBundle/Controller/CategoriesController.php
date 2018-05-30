<?php
/**
 * Categories controller.
 */
namespace AppBundle\Controller;

use AppBundle\Repository\CategoriesRepository;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoriesController.
 *
 * @Route("/categories")
 */
class CategoriesController extends Controller
{
    /**
     * Categories repository.
     *
     * @var \AppBundle\Repository\CategoriesRepository|null $categoriesRepository
     */
    protected $categoriesRepository = null;

    /**
     * CategoriesController constructor.
     *
     * @param \AppBundle\Repository\CategoriesRepository $categoriesRepository Categories repository
     */
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
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
     *     name="categories_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="categories_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        $categories = $this->categoriesRepository->findAllPaginated($page);

        return $this->render(
            'categories/index.html.twig',
            ['categories' => $categories]
        );

    }
    /**
     * View action.
     *
     * @param Category $category Category entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="categories_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Category $category)
    {
        return $this->render(
            'categories/view.html.twig',
            ['category' => $category]
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
     *     name="categories_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesRepository->save($category);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/add.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Category                     $category     Category entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="categories_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesRepository->save($category);
            $this->addFlash('success', 'message.modified_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/edit.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     * @param \AppBundle\Entity\Category                     $category     Category entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="categories_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createForm(FormType::class, $category); //nie Category ???
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoriesRepository->delete($category);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('categories_index');
        }

        return $this->render(
            'categories/delete.html.twig',
            [
                'category' => $category,
                'form' => $form->createView(),
            ]
        );
    }
}