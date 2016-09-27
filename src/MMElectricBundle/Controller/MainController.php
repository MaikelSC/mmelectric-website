<?php

namespace MMElectricBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use WebManagementBundle\Entity\quote;
use WebManagementBundle\Helpers\Paginate;
use WebManagementBundle\Form\GenericForm;
use MMElectricBundle\customs\articlesManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
	
	
	public function contractorsListAction(){
		$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$get_contractors = $category_manager->getContractors();
		return $this->render('mmelectric/pg-views/pg-contractors.html.twig', array("contractors"=> $get_contractors));
	}
	/**
	* 
	* @Route("/{next_page}", name = "home", defaults = {"next_page" = 1})
	* 
	* @return
	*/
    public function homeAction(Request $request, $next_page)
    {
    	$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$content_by = $category_manager->getContentXCategory();
    	$pag = new Paginate($content_by["projects"], 9, $next_page);	
		$content_by["projects"] = $pag->setPagination();
    	$form = $this->quoteProssesing($request);
		
        return $this->render('mmelectric/pg-views/pg-main.html.twig', array('category' => $content_by, 'form'=>$form->createView()));
    }
    
    /**
	* 
	* @Route("/company/policy/{page}", name = "policy", defaults = {"page":"privacy-policy"})
	* 
	* @return
	*/
    public function privacyPolicyAction(){
		return $this->render('mmelectric/pg-views/pg-privacy-policy.html.twig');
	}    
    
    /**
	* 
	* @Route("/company/terms/{page}", name = "terms", defaults = {"page":"terms-and-conditions"})
	* 
	* @return
	*/
    public function termsAndConditionsAction(){
		return $this->render('mmelectric/pg-views/pg-terms-and-conditions.html.twig');
	}    
    
    /**
	* 
	* @Route("/company/resume/{page}", name = "resume", defaults = {"page":"resume"})
	* 
	* @return
	*/
    public function getResumeAction(){
    	$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$get_resume = $category_manager->getArticlesBy("Resume");
		return $this->render('mmelectric/pg-views/pg-resume.html.twig', array("resume"=>$get_resume));
	}    
		
    /**
	* 
	* @Route("/company/faqs/{page}", name = "faqs", defaults = {"page":"faqs"})
	* 
	* @return
	*/
    public function getFAQsAction(){
    	$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$get_faqs = $category_manager->getArticlesBy("FAQ");
		return $this->render('mmelectric/pg-views/pg-faqs.html.twig', array("faqs"=>$get_faqs));
	}    
	
    /**
	* 
	* @Route("/projects/", name = "show_projects")
	* 
	* @return
	*/
    public function getProjectsAction(Request $request)
    {
    	$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$get_projects = $category_manager->getArticlesBy("Project");
    	$form = $this->quoteProssesing($request);
    	return $this->render('mmelectric/pg-views/pg-projects.html.twig', array('projects_arr' => $get_projects, 'form'=>$form->createView()));
    }
    /**
	* @Route("/album/photos/",  name = "get_photos_album")
	* 
	* @return
	*/
    public function getPhotosAlbumAction(Request $request)
    {
		$id_article = ($request->isMethod('POST')) ? $request->request->get('id') : $request->query->get('id');
		$category_manager = $this->get("app.mm_electric_category_manager");
    	$category_manager->Init($this);
    	$photos_album = $category_manager->getPhotosAlbum($id_article);
//		dump($id_article);die;
		return new Response(json_encode(array($photos_album)));
	}
    
    public function quoteProssesing($request){
		$quote = new quote($this);
		$items = $quote->addElementForm();
		$form = $this->createForm(new GenericForm($items), $quote);
		$form->handleRequest($request); 
		if($form->isValid())
		{		
			$quote->persistData($form->getData());
			$this->quoteEmailing($form);
			unset($form);
			unset($quote);
			$quote = new quote($this);
			$items = $quote->addElementForm();
			$form = $this->createForm(new GenericForm($items), $quote);			
			return $form;
		}
		return $form;
	}
	/**
	*  @Route("/email/to/send", name = "show_email")
	* 
	* @return
	*/
	/*public function viewEmail(){
		$quote = new quote($this);
		return $this->render('mmelectric/emails/quote-email.html.twig',
                array('data_arr'=> $quote));
	}*/
	
	public function quoteEmailing($form){
		$query_obj = $this->get("app.common_queries");
		$companyEmails = $query_obj->getRowFromTableWhereColumnValue($this, "WebManagementBundle:quote_emails", "active", "1", "email");
		$sendTo_arr = array();
		foreach($companyEmails as $key => $value){
			$sendTo_arr[$key] = $value->getEmail();
		}
		$message = \Swift_Message::newInstance()
        ->setSubject('Request for quotation')
        ->setFrom($form->getData()->getEmail())
        ->setTo($sendTo_arr)
        ->setBody(
            $this->renderView(
                'mmelectric/emails/quote-email.html.twig',
                array('data_arr'=> $form->getData())
            ),
            'text/html'
        )
//         If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'mmelectric/emails/quote-email.txt.twig',
                array('data_arr'=> $form->getData())
            ),
            'text/plain'
        );
	    $this->get('mailer')->send($message);
	    
	    $message = \Swift_Message::newInstance()
        ->setSubject('Quotation request received!')
        ->setFrom('no-reply@mmelectriccfl.com')
        ->setTo($form->getData()->getEmail())
        ->setBody(
            $this->renderView(
                'mmelectric/emails/user-auto-confirm.html.twig',
                array('data_arr'=> $form->getData())
            ),
            'text/html'
        )
//         If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'mmelectric/emails/user-auto-confirm.txt.twig',
                array('data_arr'=> $form->getData())
            ),
            'text/plain'
        );
	    $this->get('mailer')->send($message);
		
	}
}
