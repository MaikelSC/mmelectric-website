<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\user_rolesRepository")
* @ORM\Table(name="tb_user_roles")
* @UniqueEntity(fields={"role"}, message="Sorry, this role already exist.")
*/
class user_roles
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_roles;
	
	/**
	* @ORM\Column(type="string", length=50)	
	*/
	private $role;
	
	private $controller_obj;
    /**
     * Constructor
     */
    public function __construct($controller_obj = null)
    {
       
        $this->controller_obj = $controller_obj;
    }

    /**
     * Get id_roles
     *
     * @return integer 
     */
    public function getIdRoles()
    {
        return $this->id_roles;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return roles
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->role;
    }
    
//---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:user_roles", $column, $value, 'role');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:user_roles", 'role');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdRoles();
				$show_list[$i]['Role']= $table_list_query[$i]->getRole();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Role']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
		$fields = array("role");
		$fields_types = array("text");
		$options = ($add_options != NULL )? $add_options : array(NULL);
		$items['entity'] = "\user_roles";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------

//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new user_roles();
		$new_instance->setRole($data_obj->getRole());		
		$em = $this->controller_obj->getDoctrine()->getManager();		
		$em->persist($new_instance);
		$em->flush();
	}
//------------------------------------------//---PERSIST DATA---//-------------------------------------------------------
	
//---------------------------------------------REMOVE ITEM---------------------------------------------------------------
	public function removeItems($items_ids)
	{
		$items_ids_list;
		if(!is_array($items_ids)){
			$items_ids_list[]= $items_ids;
		}
		else{
			$items_ids_list = $items_ids;
		}
		$em = $this->controller_obj->getDoctrine()->getManager();
		$current_repo = $em->getRepository("WebManagementBundle:user_roles");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$items_found[$i] = $current_repo->find($items_ids_list[$i]);
			$em->remove($current_repo->find($items_ids_list[$i]));
		}
		if(count($items_found) > 0){
			$em->flush();
			return true;
		}
		else{
			return false;
		}		
	}
//------------------------------------------//---REMOVE ITEM---//--------------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM FORM--------------------------------------------------------
    public function updateItemForm($item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:user_roles");
		$item_found = $entity_repo->find($item_id);
		$common_query = $this->controller_obj->get('app.common_queries');
		$options = array(
						 array("data"=>$item_found->getRole())
						);
		$form_array['items'] = $this->addElementForm($options);
		$form_array['instance'] = $item_found;
		return $form_array;
	}
//------------------------------------------//-----UPDATE ITEM FORM----//------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM ACTION------------------------------------------------------
    public function updateData($data_obj, $item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$item = $em->getRepository("WebManagementBundle:user_roles")->find($item_id);
		$item->setRole($data_obj->getRole());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//----------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:user_roles", "id_roles", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Roles: </strong>".$row[0]->getIdRoles()."</div>
								<div>"."<strong> Role: </strong> ".$row[0]->getRole()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("role");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
