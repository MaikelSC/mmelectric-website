<?php
namespace WebManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebManagementBundle\Helpers\CommonQueries;


class SecurityController extends Controller
{
   /**
     * @Route("/wms/login", name="login_route")
     */
    public function loginAction(Request $request)
    {
    	 $authenticationUtils = $this->get('security.authentication_utils');
		/*dump($authenticationUtils);
		die;*/
	    // get the login error if there is one
	    $error = $authenticationUtils->getLastAuthenticationError();
		/*dump($error);
		die;*/
	    // last username entered by the user
	    $lastUsername = $authenticationUtils->getLastUsername();

	    return $this->render(
	        'webmanager/pg-views/pg_login.html.twig',
	        array(
	            // last username entered by the user
	            'last_username' => $lastUsername,
	            'error'         => $error,
	        )
	    );
    }

    /**
     * @Route("/back-end/login-check", name="login_check")
     */
    public function loginCheckAction()
    {
       
    }   
}

?>