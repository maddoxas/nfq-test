<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;

class GroupController extends Controller
{
   /**
   	 * Group list action.
     * 
     * @Route("/", name="home")
     * @Route("/groups", name="groups")
     * 
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     */
    public function listAction(Request $request)
    {
        return $this->render('group/list.html.twig', [
            'groups' => $this->getGroups()
        ]);
    }

    /**
     * Create group action.
     *
     * @Route("/groups/create", name="group_create")
     * 
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $group = new Group();

        $form = $this->buildGroupForm($group);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveGroup($group);

            return $this->redirectToRoute('groups');
        }

        return $this->render('group/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete group action.
     *
     * @Route("/groups/delete/{id}", name="group_delete")
     *
     * @param int id group id
     *
     * @return Response A Response instance
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $group = $this->getGroupById($id);

        if ($group != null) {
            $this->deleteGroup($group);

            return $this->redirectToRoute('groups');
        }

        return $this->redirectToRoute('groups');
    }


    /**
     * Manage group users action.
     *
     * @Route("/groups/{id}/users", name="group_users")
     *
     * @param int id group id
     *
     * @return Response A Response instance
     */
    public function usersAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $group = $this->getGroupById($id);
        $users = [];

        if ($group) {
            return $this->render('group/users.html.twig', [
               'group' => $group,
               'unassigned_users' => $this->getUsersNotAddedToGroup($group),
            ]);
        }

        throw new NotFoundHttpException('Group not found.');
    }


    /**
     * Add user action.
     *
     * @Route("/groups/{groupId}/user/{userId}/add", name="add_group_user")
     *
     * @param int id group id
     *
     * @return Response A Response instance
     */
    public function addUserAction($groupId, $userId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $group = $this->getGroupById($groupId);

        if ($group) {
            $user = $this->getUserById($userId);

            if ($user) {
                if (!$group->hasUser($user)) {
                    $this->saveGroup($group->addUser($user));               
                }

                return $this->redirectToRoute('group_users', ['id' => $groupId]);
            }

            throw new NotFoundHttpException('User not found.');
        }
       
        throw new NotFoundHttpException('Group not found.');
    }

    /**
     * Remove user action.
     *
     * @Route("/groups/{groupId}/user/{userId}/remove", name="remove_group_user")
     *
     * @param int id group id
     *
     * @return Response A Response instance
     */
    public function removeUserAction($groupId, $userId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $group = $this->getGroupById($groupId);

        if ($group) {
            $user = $this->getUserById($userId);

            if ($user) {
                if ($group->hasUser($user)) {
                    $group->removeUser($user);
                    $this->saveGroup($group);               
                }

                return $this->redirectToRoute('group_users', ['id' => $groupId]);
            }

            throw new NotFoundHttpException('User not found.');
        }
       
        throw new NotFoundHttpException('Group not found.');
    }


    /**
     * Builds a group creation form.
     *
     * @param group $group
     *
     * @return Symfony\Component\Form\Form A built form
     */
    private function buildGroupForm(Group $group)
    {
       return $this->createFormBuilder($group)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Group'))
            ->getForm()
        ;
    } 

    /**
     * Saves a new group.
     *
     * @param Symfony\Component\Form\Form A form submitted
     *
     * @throws 
     * 
     */
    private function saveGroup(Group $group)
    {        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($group);
        $em->flush();
    }


    /**
     * 
     * Gets group by an id.
     *
     * @return group|null
     */
    private function getGroupById(int $id)
    {   
        return $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository(Group::class)
            ->getGroupById($id)
        ;
    }

    /**
     *
     * Deletes group.
     *
     * @param Group $group
     *
     */
    private function deleteGroup(Group $group)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($group);
        $em->flush();
    }

    /**
     * Returns users who are not assigned to any group
     *
     * @param Group $group
     *
     * @return array Users
     */
     private function getUsersNotAddedToGroup(Group $group)
     {
        return $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository(User::class)
            ->getUsersWhoAreNotAddedToGroup($group)
        ;
     }


     /**
     * 
     * Gets user by an id.
     *
     * @return User|null
     */
    private function getUserById(int $id)
    {   
        return $this
            ->getDoctrine()
            ->getEntityManager()
            ->getRepository(User::class)
            ->getUserById($id);
        ;
    }

    /**
     * Gets all groups.
     *
     * @return Doctrine\ORM\PersitentCollection Groups
     */
    private function getGroups()
    {
        return $this
          ->getDoctrine()
          ->getRepository(Group::class)
          ->getGroups()
        ;
    }
}
