<?php
/**
 * Users controller.
 */
namespace UserBundle\Controller;

use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Class UsersController.
 *
 * @package UserBundle\Controller
 *
 * @Route("/users")
 */
class UsersController extends Controller
{
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
     *     name="users_index",
     * )
     * @Route(
     *     "/page/{page}",
     *     requirements={"page": "[1-9]\d*"},
     *     name="users_index_paginated",
     * )
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        if ($this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN') != 'Access Denied.') {
            $userManager = $this->get('fos_user.user_manager');
            $users = $userManager->findUsers();

            return $this->render(
                'users/index.html.twig',
                ['users' => $users]
            );
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }
    }

    /**
     * View action.
     *
     * @param User $user User entity
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP      Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @Route(
     *     "/{id}",
     *     requirements={"id": "[1-9]\d*"},
     *     name="users_view",
     * )
     * @Method("GET")
     */
    public function viewAction(User $user, Request $request)
    {
        if ($this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN') != 'Access Denied.') {
            return $this->render(
                'users/view.html.twig',
                ['user' => $user]
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
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP      Request
     * @param \UserBundle\Entity\User                    $user      User      entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     requirements={"id": "[1-9]\d*"},
     *     name="users_delete",
     * )
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request, User $user)
    {
        if ($this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN') != 'Access Denied.') {
            $userManager = $this->get('fos_user.user_manager');
            $form = $this->createForm(FormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userManager->deleteUser($user);
                $this->addFlash('success', 'message.deleted_successfully');

                return $this->redirectToRoute('users_index');
            }

            return $this->render(
                'users/delete.html.twig',
                [
                    'user' => $user,
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
     * Promote action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP      Request
     * @param \UserBundle\Entity\User                    $user      User      entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/promote",
     *     requirements={"id": "[1-9]\d*"},
     *     name="users_promote",
     * )
     * @Method({"GET", "POST"})
     */
    public function promoteUserAction(Request $request, User $user)
    {
        if ($this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN') != 'Access Denied.') {
            $userManager = $this->get('fos_user.user_manager');
            $user->addRole('ROLE_SUPER_ADMIN');
            $userManager->updateUser($user);
            return $this->forward('UserBundle\Controller\UsersController::indexAction');
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }
    }

    /**
     * Demote action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP      Request
     * @param \UserBundle\Entity\User                    $user      User      entity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response HTTP Response
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/demote",
     *     requirements={"id": "[1-9]\d*"},
     *     name="users_demote",
     * )
     * @Method({"GET", "POST"})
     */
    public function demoteUserAction(Request $request, User $user)
    {
        if ($this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN') != 'Access Denied.') {
            $userManager = $this->get('fos_user.user_manager');
            $user->removeRole('ROLE_SUPER_ADMIN');
            $userManager->updateUser($user);
            return $this->forward('UserBundle\Controller\UsersController::indexAction');
        } else {
            $response = $this->forward('FOS\UserBundle\Controller\SecurityController::loginAction', array(
                $request
            ));
            return $response;
        }
    }
}
