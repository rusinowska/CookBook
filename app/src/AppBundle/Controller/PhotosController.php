<?php
/**
 * Photos controller.
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Recipe;
use AppBundle\Form\PhotoType;
use AppBundle\Repository\PhotosRepository;
use AppBundle\Repository\RecipesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class PhotosController.
 *
 * @Route("/photos")
 */
class PhotosController extends Controller
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
     * Photos repository.
     *
     * @var \AppBundle\Repository\PhotosRepository|null $photosRepository
     */
    protected $photosRepository = null;

    /**
     * Recipes repository.
     *
     * @var \AppBundle\Repository\RecipesRepository|null $recipesRepository
     */
    protected $recipesRepository = null;

    /**
     * PhotosController constructor.
     *
     * @param \AppBundle\Repository\PhotosRepository $photosRepository Photos repository
     * @param \AppBundle\Repository\RecipesRepository $recipesRepository Recipes repository
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authorizationChecker
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(PhotosRepository $photosRepository, RecipesRepository $recipesRepository, AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $tokenStorage)
    {
        $this->photosRepository = $photosRepository;
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
     *     name="photos_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="photos_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction($page)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $photos = $this->photosRepository->findAllPaginated($page);

            return $this->render(
                'photos/index.html.twig',
                ['photos' => $photos]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction');
            return $response;
        }

    }
    /**
     * View action.
     *
     * @param Photo $photo Photo entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="photos_view",
     * )
     * @Method("GET")
     */
    public function viewAction(Photo $photo)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $recipes = $photo->getRecipes();

            return $this->render(
                'photos/view.html.twig',
                [
                    'photo' => $photo,
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
     *     name="photos_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $photo = new Photo();
            $form = $this->createForm(PhotoType::class, $photo);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->photosRepository->save($photo);
                $this->addFlash('success', 'message.created_successfully');

                return $this->redirectToRoute('photos_index');
            }

            return $this->render(
                'photos/add.html.twig',
                [
                    'photo' => $photo,
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
     * @param \AppBundle\Entity\Photo                     $photo     Photo entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     requirements={"id": "[1-9]\d*"},
     *     name="photos_edit",
     * )
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Photo $photo)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form = $this->createForm(PhotoType::class, $photo);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->photosRepository->save($photo);
                $this->addFlash('success', 'message.modified_successfully');

                return $this->redirectToRoute('photos_index');
            }

            return $this->render(
                'photos/edit.html.twig',
                [
                    'photo' => $photo,
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
     * @param \AppBundle\Entity\Photo                     $photo     Photo entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="photos_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, Photo $photo)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
            $form = $this->createForm(FormType::class, $photo); //nie Photo ???
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->photosRepository->delete($photo);
                $this->addFlash('success', 'message.deleted_successfully');

                return $this->redirectToRoute('photos_index');
            }

            return $this->render(
                'photos/delete.html.twig',
                [
                    'photo' => $photo,
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