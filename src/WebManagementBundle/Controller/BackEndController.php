<?php
namespace WebManagementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use WebManagementBundle\Form\GenericForm;
use WebManagementBundle\Helpers\CommonQueries;
use WebManagementBundle\Helpers\Paginate;
use WebManagementBundle\Entity\user;
use WebManagementBundle\Entity\user_roles;
use WebManagementBundle\Entity\contact;

class BackEndController extends Controller
{
//------------------------------------------------------------------------------------------------------------------------------
// 						1-	Method to render the classes that will fill the system menu.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	*
	*/
	public function BackEndListAction()
	{
		$common_query = $this->get('app.common_queries'); /*new CommonQueries();*/
		$controller = $this;	
		$back_end_list= $common_query->getRowFromTableWhereColumnValue($controller, "WebManagementBundle:back_end", "active", "1", "tb_name");
//		$back_end_list= $common_query->getAllOrderedByFieldFromTable($controller, "WebManagementBundle:back_end", "tb_name" );
		return $this->render('webmanager/pg-views/pg_tables-list.html.twig', array('back_end_list'=> $back_end_list));
	}

//------------------------------------------------------------------------------------------------------------------------------	
//						2-	Method to provide users access to the system.
//------------------------------------------------------------------------------------------------------------------------------	

	/**
	* @Route("/wms/management", name="login_success")
	* 
	*/
	public function managementAccessAction()
	{
		$this->denyAccessUnlessGranted(array("ROLE_ADMIN","ROLE_USER"),NULL, "Sorry, no access granted to your credentials.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
//			return new Response('"Sorry, You must login with a valid username to reach this address"');
		}		
		$dependencies_arr = $this->getDBDependencies();
		$authenticated_user = $this->get('security.token_storage')->getToken()->getUser();
		return $this->render( 'webmanager/pg-views/pg_main-back-end-tb-dependencies.html.twig', array('username'=>$authenticated_user->getUsername(), 'tb_dependencies_arr'=> $dependencies_arr));		
	}	

//------------------------------------------------------------------------------------------------------------------------------
//						3-	Method to show all the records of a given table.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	* @Route("/wms/management/show/{table}/{column}/{value}/{next_page}/{items_x_page}" , name = "show_table" , defaults = {"next_page" = 1, "items_x_page" = 10, "column" = "all", "value" = "values"})
	*/
	public function showEntityItemsAction(Request $request, $table, $column, $value, $next_page, $items_x_page)
	{
		$this->denyAccessUnlessGranted(array("ROLE_ADMIN","ROLE_USER"),NULL, "Sorry, not enough privileges to access.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
		}
		$className ="WebManagementBundle\Entity\\".(substr($table, 3)) ;	//Getting the class name from the view and creating the related model Name	
		$entity_obj = new $className($this); //Creating object of the related model class
		$common_query = $this->get('app.common_queries'); // getting the common queries service
		$table_columns = $entity_obj->getfilterColumns();		
		$column_values_arr = array();
		$column_param = NULL;
		$value_param = NULL;
		if($column != "all"){
			
			$column_values_query = $common_query->getColumnValuesFromTable($this, $table, $column);
			
			foreach($column_values_query as $column_values){
				foreach($column_values as $key => $val){
					$column_values_arr[] = $val;
				}				
			}
			$column_values_arr = array_unique($column_values_arr);
			
			if($value != 'values'){
				$column_param = preg_replace("/_id/", "", $column);
				$value_param = $value;
			}
		}
		$items_list = $entity_obj->showList($column_param, $value_param);
		$pag = new Paginate($items_list, $items_x_page, $next_page);
		$pagination_obj = $pag->setPagination();
		$dependencies_arr = $this->getDBDependencies($table);
		
		return $this->render('webmanager/pg-views/pg_main-back-end-list.html.twig' , array('tb_columns' => $table_columns, 'selected_col' => $column, 'selected_val' => $value,'column_values'=>$column_values_arr, 'pagination_obj'=>$pagination_obj, "active_table"=>$table, 'tb_dependencies_arr'=> $dependencies_arr[$table]));
	}
	
//------------------------------------------------------------------------------------------------------------------------------
//						4-	Method to show a viewfinder with any row's records of a given table.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	* @Route("/wms/management/show/" , name = "get_table_records")
	*/
	public function getTableRecordsAction(Request $request)
	{
		$this->denyAccessUnlessGranted(array("ROLE_ADMIN","ROLE_USER"),NULL, "Sorry, no access granted to your credentials.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
		}
		$table = ($request->isMethod('POST'))  ? $request->request->get("table") : $request->query->get("table");
		$id = ($request->isMethod('POST')) ? $request->request->get('id') : $request->query->get('id');
		$className ="WebManagementBundle\Entity\\".(substr($table, 3)) ;		
		$entity_obj = new $className($this);
		$items_list = $entity_obj->viewFinder($id);
		return new Response(json_encode(array("table_records"=>$items_list)));
	}

//------------------------------------------------------------------------------------------------------------------------------
//						5-	Method to create a form to add a new record on a table.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	* @Route("/wms/management/add/{table}" , name = "add_item")
	*/
	public function generateFormViewAction(Request $request, $table)
	{
		$this->denyAccessUnlessGranted("ROLE_ADMIN",NULL, "Sorry, no access granted to your credentials.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
		}		
		$className ="WebManagementBundle\Entity\\".(substr($table, 3)) ;	
		$entityType = new $className($this);
		$items = $entityType->addElementForm();
		$form = $this->createForm(new GenericForm($items), $entityType);
		$form->handleRequest($request); 
		if($form->isValid())
		{		
			$entityType->persistData($form->getData());
			
			return $this->redirectToRoute("show_table",array("table"=>$table));
		}
		$dependencies_arr = $this->getDBDependencies($table);	
		return $this->render('webmanager/pg-views/pg_main-back-end-form.html.twig', array('form'=>$form->createView(), "active_table"=>$table, 'tb_dependencies_arr'=> $dependencies_arr[$table]));
	}
	
//------------------------------------------------------------------------------------------------------------------------------
//						6-	Method to remove any row from a given table.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	* @Route("/wms/management/delete/", name="remove_item")
	*/
	public function removeItemAction(Request $request)
	{
		$this->denyAccessUnlessGranted("ROLE_ADMIN",NULL, "Sorry, no access granted to your credentials.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
		}	
		$table = ($request->isMethod('POST'))  ? $request->request->get("table") : $request->query->get("table");
		$ids = ($request->isMethod('POST')) ? $request->request->get('ids') : $request->query->get('ids');
		/*dump($ids);
		die;*/
		$className ="WebManagementBundle\Entity\\".(substr($table, 3)) ;	
		$entity_obj = new $className($this);
		$deleted_rows = $entity_obj->removeItems($ids);
		if($request->isXmlHttpRequest()){
			if($deleted_rows){
				return new Response(json_encode(array()));
			}
			else{
				header('HTTP/1.1 500 Internal Server Error');
	        	header('Content-Type: application/json; charset=UTF-8');
				die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
			}
		}
		else{
			return $this->redirectToRoute("show_table",array("table"=>$table));
		}
	}

//------------------------------------------------------------------------------------------------------------------------------
//						7-	Method to open any table's records on a form to be edited.
//------------------------------------------------------------------------------------------------------------------------------

	/**
	* @Route("/wms/management/edit/", name="edit_item")
	*/
	public function editItemAction(Request $request)
	{
		$this->denyAccessUnlessGranted("ROLE_ADMIN",NULL, "Sorry, no access granted to your credentials.");
		if(!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			throw $this->createAccessDeniedException("Sorry, You must login with a valid username to reach this address");
		}	
		$table = $request->query->get("table");	
		$id = $request->query->get('id');	
		$className ="WebManagementBundle\Entity\\".(substr($table, 3)) ;	
		$entity_obj = new $className($this);
		$form_array = $entity_obj->updateItemForm($id);
		$form = $this->createForm(new GenericForm($form_array['items']), $form_array['instance']);
		$form->handleRequest($request);
		if($form->isValid())
		{			
			$entity_obj->updateData($form->getData(), $id);
			
			return $this->redirectToRoute("show_table",array("table"=>$table));
		}
		$dependencies_arr = $this->getDBDependencies($table);	
		return $this->render('webmanager/pg-views/pg_main-back-end-form.html.twig', array('form'=>$form->createView(), "active_table"=>$table, 'tb_dependencies_arr'=> $dependencies_arr[$table]));
	}

//--------------------------------------------------------------------------------------------------------------------------------
//						8- Method to retrieve all the database's tables dependencies.
//--------------------------------------------------------------------------------------------------------------------------------

	private function getDBDependencies($table = NULL)
	{
		$common_query = $this->get('app.common_queries');
		$dependencies_arr = $common_query->getAllDBDependencies($this, $table); 
		return $dependencies_arr;
	}
	
//--------------------------------------------------------------------------------------------------------------------------------
//
//--------------------------------------------------------------------------------------------------------------------------------


}
?>