<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\categoryRepository")
* @ORM\Table(name="tb_category")
* @UniqueEntity(fields={"name"}, message="Sorry, this category already exist.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class category {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_category;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 20) 
	* @Assert\NotBlank()
	*/
	private $name;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 300) 
	* @Assert\NotBlank()
	*/
	private $description;
	
	private $controller_obj;

	function __construct($controller_obj = NULL)
	{
			$this->controller_obj = $controller_obj; 
	}

    /**
     * Get id_category
     *
     * @return integer 
     */
    public function getIdCategory()
    {
        return $this->id_category;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return category
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
    
    //---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)	
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:category", $column, $value, 'name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:category", 'name');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdCategory();
				$show_list[$i]['Table name']= $table_list_query[$i]->getName();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Table name']= NULL;
			$show_list[0]['Description'] = NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
		$fields = array("name","description");
		$fields_types = array("text", "textarea");
		$options = ($add_options != NULL )? $add_options : array(NULL, NULL);
		$items['entity'] = "\category";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
	
//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new category();
		$new_instance->setName($data_obj->getName());
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
		$current_repo = $em->getRepository("WebManagementBundle:category");
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
		$entity_repo = $em->getRepository("WebManagementBundle:category");
		$item_found = $entity_repo->find($item_id);
		$common_query = $this->controller_obj->get('app.common_queries');
		$options = array(
						 array("data"=>$item_found->getName()),
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
		$item = $em->getRepository("WebManagementBundle:category")->find($item_id);
		$item->setName($data_obj->getName());
		$item->setDescription($data_obj->getDescription());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//----------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:category", "id_category", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Category: </strong>".$row[0]->getIdCategory()."</div>
								<div>"."<strong> Name: </strong> ".$row[0]->getName()."</div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("name");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 

}
