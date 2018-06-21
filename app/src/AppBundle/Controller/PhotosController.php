<?php
/**
 * Photos controller.
 */
namespace AppBundle\Controller;

use AppBundle\Repository\PhotosRepository;
use AppBundle\Entity\Photo;
use AppBundle\Form\PhotoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PhotosController.
 *
 * @Route("/photos")
 */
class PhotosController extends Controller
{
    /**
     * Photos repository.
     *
     * @var \AppBundle\Repository\PhotosRepository|null $photosRepository
     */
    protected $photosRepository = null;

    /**
     * PhotosController constructor.
     *
     * @param \AppBundle\Repository\PhotosRepository $photosRepository Photos repository
     */
    public function __construct(PhotosRepository $photosRepository)
    {
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
        $photos = $this->photosRepository->findAllPaginated($page);

        return $this->render(
            'photos/index.html.twig',
            ['photos' => $photos]
        );

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
        return $this->render(
            'photos/view.html.twig',
            ['photo' => $photo]
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
     *     name="photos_add",
     * )
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
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
    }
}