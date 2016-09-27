<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\backEndRepository")
* @ORM\Table(name="tb_back_end")
* @UniqueEntity(fields={"tb_name"}, message="Sorry, this table already exist.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class back_end {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_back_end;
	/**
	* 
	* @ORM\Column(type="string", length = 100)
	* @Assert\Regex(pattern = "/^tb_+/", message = "The table name must start with 'tb_'  prefix")
	*/
	
	private $tb_name;
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	*/
	private $active;
	
	/**
	* 
	* @ORM\Column(type="string", length = 300)
	* 
	*/
	private $description;
	
	private $controller_obj;
	function __construct($controller_obj = null) {
		$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_back_end
     *
     * @return integer 
     */
    public function getIdBackEnd()
    {
        return $this->id_back_end;
    }

    /**
     * Set tb_name
     *
     * @param string $tbName
     * @return backEnd
     */
    public function setTbName($tbName)
    {
        $this->tb_name = $tbName;

        return $this;
    }

    /**
     * Get tb_name
     *
     * @return string 
     */
    public function getTbName()
    {
        return $this->tb_name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return backEnd
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
     /**
     * Set active
     *
     * @param boolean $active
     * @return back_end
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    
//---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)	
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:back_end", $column, $value, 'tb_name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:back_end", 'tb_name');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdBackEnd();
				$show_list[$i]['Table name']= $table_list_query[$i]->getTbName();
				$show_list[$i]['Active']= $table_list_query[$i]->getActive()? "YES": "NO";
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Table name']= NULL;
			$show_list[0]['Active'] = NULL;
			$show_list[0]['Description'] = NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
		$fields = array("tb_name", "active", "description");
		$fields_types = array("text", "checkbox", "textarea");
		$options = ($add_options != NULL )? $add_options : array(NULL, array('data'=>true, 'required'=>FALSE), NULL);
		$items['entity'] = "\back_end";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
	
//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new back_end();
		$new_instance->setTbName($data_obj->getTbName());
		$new_instance->setActive($data_obj->getActive());			
		$new_instance->setDescription($data_obj->getDescription());			
		$em = $this->controller_obj->getDoctrine()->getManager();
		$em->persist($new_instance);
		$em->flush();
	}	
//------------------------------------------//---PERSIST USER DATA---//--------------------------------------------------

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
		$current_repo = $em->getRepository("WebManagementBundle:back_end");
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
	//------------------------------------------//---REMOVE ITEM---//----------------------------------------------------
	 public function updateItemForm($item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:back_end");
		$item_found = $entity_repo->find($item_id);
		$common_query = $this->controller_obj->get('app.common_queries');
		$activeDataDisabled = false;
		if($item_found->getTbName() === "tb_back_end"){
			$activeDataDisabled = true;
		}
		$options = array(
						 array("data"=>$item_found->getTbName()),
						 array("data"=>$item_found->getActive(), "disabled"=>$activeDataDisabled, 'required'=>FALSE),
						 array("data"=>$item_found->getDescription()),
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
		$item = $em->getRepository("WebManagementBundle:back_end")->find($item_id);
		$item->setTbName($data_obj->getTbName());
		$item->setActive($data_obj->getActive());
		$item->setDescription($data_obj->getDescription());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//----------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:back_end", "id_back_end", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Back End: </strong>".$row[0]->getIdBackEnd()."</div>
								<div>"."<strong> Name: </strong> ".$row[0]->getTbName()."</div>
								<div>"."<strong> Active: </strong> ".($row[0]->getActive()? "YES": "NO")."</div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("active");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
