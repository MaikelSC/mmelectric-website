<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\writerRepository")
* @ORM\Table(name="tb_writer")
* @UniqueEntity(fields={"name", "last_name"}, message="Sorry, this writer already exist.")
* @UniqueEntity(fields={"web_url"}, message="You are referring to an existent url.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class writer {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_writer;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 20)
	* @Assert\NotBlank()
	*/
	private $name;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 20)
	* @Assert\NotBlank()
	*/
	private $last_name;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 20)
	* @Assert\NotBlank()
	*/
	private $country;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $web_url;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 300000)
	* @Assert\NotBlank()
	*/
	private $description;
	
	private $controller_obj;

	function __construct($controller_obj = NULL)
	{
			$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_writer
     *
     * @return integer 
     */
    public function getIdWriter()
    {
        return $this->id_writer;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return writer
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
     * Set last_name
     *
     * @param string $lastName
     * @return writer
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return writer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set web_url
     *
     * @param string $webUrl
     * @return writer
     */
    public function setWebUrl($webUrl)
    {
        $this->web_url = $webUrl;

        return $this;
    }

    /**
     * Get web_url
     *
     * @return string 
     */
    public function getWebUrl()
    {
        return $this->web_url;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return writer
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
    
    //----------------------------------------CLEAN STRING--------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//------------------------------------------------------------
	
//---------------------------------------------GENERATE LIST VIEW---------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:writer", $column, $value, 'name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:writer", 'name');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdWriter();
				$show_list[$i]['Name']= $table_list_query[$i]->getName();
				$show_list[$i]['Last name']= $table_list_query[$i]->getLastName();
				$show_list[$i]['Country']= $table_list_query[$i]->getCountry();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Name']= NULL;
			$show_list[0]['Last name']= NULL;
			$show_list[0]['Country']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//--------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS----------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
		$fields = array("name","last_name", "country", "web_url", "description");
		$fields_types = array("text", "text", "text", "url", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(NULL, NULL, NULL, NULL, NULL);
		$items['entity'] = "\writer";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS----------------------------------------------------
	
//---------------------------------------------PERSIST DATA---------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new writer();
		$new_instance->setName($data_obj->getName());
		$new_instance->setLastName($data_obj->getLastName());
		$new_instance->setCountry($data_obj->getCountry());				
		$new_instance->setWebUrl($data_obj->getWebUrl());			
		$new_instance->setDescription($data_obj->getDescription());			
		$em = $this->controller_obj->getDoctrine()->getManager();
		$em->persist($new_instance);
		$em->flush();
	}	
//------------------------------------------//---PERSIST USER DATA---//---------------------------------------------------
		
//---------------------------------------------REMOVE ITEM----------------------------------------------------------------
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
		$current_repo = $em->getRepository("WebManagementBundle:writer");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$current_row = $current_repo->find($items_ids_list[$i]);
			$items_found[$i] = $current_row;
			$em->remove($current_row);
		}
		if(count($items_found) > 0){
			$em->flush();
			return true;
		}
		else{
			return false;
		}		
	}
//------------------------------------------//---REMOVE ITEM---//---------------------------------------------------------
	
//------------------------------------------//-----UPDATE ITEM FORM----//-------------------------------------------------
	public function updateItemForm($item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:writer");
		$item_found = $entity_repo->find($item_id);
		$common_query = $this->controller_obj->get('app.common_queries');
		$options = array(
						 array("data"=>$item_found->getName()),
						 array("data"=>$item_found->getLastName()),
						 array("data"=>$item_found->getCountry()),
						 array("data"=>$item_found->getWebUrl()),
						 array("data"=>$item_found->getDescription())						 
						);
		$form_array['items'] = $this->addElementForm($options);
		$form_array['instance'] = $item_found;
		return $form_array;
	}
//------------------------------------------//-----UPDATE ITEM FORM----//-------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM ACTION-------------------------------------------------------
    public function updateData($data_obj, $item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$item = $em->getRepository("WebManagementBundle:writer")->find($item_id);
		$item->setName($data_obj->getName());			
		$item->setLastName($data_obj->getLastName());
		$item->setCountry($data_obj->getCountry());		
		$item->setWebUrl($data_obj->getWebUrl());	
		$item->setDescription($data_obj->getDescription());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//-----------------------------------------------
//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:writer", "id_writer", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Writer: </strong>".$row[0]->getIdWriter()."</div>
								<div>"."<strong> First Name: </strong> ".$row[0]->getName()."</div>
								<div>"."<strong> Last Name: </strong> ".$row[0]->getLastName()."</div>
								<div>"."<strong> Country: </strong> ".$row[0]->getCountry()."</div>
								<div>".'<strong> Url Web: </strong> <a href = "'.$row[0]->getWebUrl().'">'.$row[0]->getWebUrl()."</a></div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("name","last_name", "country");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
