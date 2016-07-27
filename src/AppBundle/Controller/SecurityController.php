<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
   /**
   	 * Login action.
     * 
     * @Route("/login", name="login")
     * 
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     */
    public function loginAction(Request $request)
    {       
       $authenticationUtils = $this->get('security.authentication_utils');

       return $this->render(
           'security/login.html.twig',
           [
               'last_username' => $authenticationUtils->getLastUsername(),
               'error'         => $authenticationUtils->getLastAuthenticationError(),
           ]
       );
    }


}
