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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class CategoriesController.
 *
 * @Route("/categories")
 */
class CategoriesController extends Controller
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
     * Categories repository.
     *
     * @var \AppBundle\Repository\CategoriesRepository|null $categoriesRepository
     */
    protected $categoriesRepository = null;


    /**
     * CategoriesController constructor.
     *
     * @param \AppBundle\Repository\CategoriesRepository                                          $categoriesRepository Categories repository
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface        $authorizationChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(CategoriesRepository $categoriesRepository, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->categoriesRepository = $categoriesRepository;
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
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $categories = $this->categoriesRepository->findAllPaginated($page);

            return $this->render(
                'categories/index.html.twig',
                ['categories' => $categories]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');

            return $response;
        }
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
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render(
                'categories/view.html.twig',
                ['category' => $category]
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
     *     name="categories_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
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
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP Request
     * @param \AppBundle\Entity\Category                $category Category entity
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
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
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
     * @param \Symfony\Component\HttpFoundation\Request $request  HTTP Request
     * @param \AppBundle\Entity\Category                $category Category entity
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
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $form = $this->createForm(FormType::class, $category);
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
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request,
            ));

            return $response;
        }
    }
}
