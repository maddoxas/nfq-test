<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Group;
use AppBundle\Controller\Exception\UserNotValidException;

class UserController extends Controller
{
   /**
   	 * User list action.
     * 
     * @Route("/", name="home")
     * @Route("/users", name="users")
     * 
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     */
    public function listAction(Request $request)
    {
       $users = $this
          ->getDoctrine()
          ->getRepository(User::class)
          ->findAll();

      return $this->render('user/list.html.twig', [
          'users' => $users
      ]);
    }

    /**
     * Create user action.
     *
     * @Route("/users/create", name="user_create")
     * 
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $user = new User();

        $form = $this->buildUserForm($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveNewUser($user);

            return $this->redirectToRoute('users');
        }

        return $this->render('user/create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete user action.
     *
     * @Route("/users/delete/{id}", name="user_delete")
     *
     * @param int id User id
     *
     * @return Response A Response instance
     */
    public function deleteAction(int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        $user = $this->getUserById($id);

        if ($user != null) {
            $this->deleteUser($user);

            return $this->redirectToRoute('users');
        }

         return $this->redirectToRoute('users');
    }


    /**
     * Add to group action
     *
     * @Route("/users/{user_id}/group/{group_id}", name="user_add_to_group")
     *
     * @param int id User id
     * @param int id Group id
     *
     * @return Response A Response instance
     */
    public function addToGroupAction($userId, $groupId)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
    }



    /**
     * Builds a user creation form.
     *
     * @param User $user
     *
     * @return Symfony\Component\Form\Form A built form
     */
    private function buildUserForm(User $user)
    {
       return $this->createFormBuilder($user)
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('save', SubmitType::class, array('label' => 'Create User'))
            ->getForm()
        ;
    } 

    /**
     * Saves a new user.
     *
     * @param Symfony\Component\Form\Form A form submitted
     *
     * @throws 
     * 
     */
    private function saveNewUser(User $user)
    {        
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
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
            ->find($id);
        ;
    }

    /**
     *
     * Deletes user.
     *
     * @param User $user
     *
     */
    private function deleteUser(User $user)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
