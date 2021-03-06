<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\locationRepository")
* @ORM\Table(name="tb_location")
* @UniqueEntity(fields={"latitude"}, message="Sorry, this lat. cordinate already exist.")
* @UniqueEntity(fields={"longitude"}, message="Sorry, this long. cordinate already exist.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class location {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_location;
	/**
	* 
	* @ORM\Column(type="string", length = 50)
	* @Assert\Regex(pattern = "/^(\-?\d+(\.\d+)?)/", message = "Please enter a valid latitude value.")
	*/
	
	private $latitude;
	
	/**
	* 
	* @ORM\Column(type="string", length = 50)
	* @Assert\Regex(pattern = "/^(\-?\d+(\.\d+)?)/", message = "Please enter a valid longitude value.")
	*/
	private $longitude;
	
	/**
	* 
	* @ORM\Column(type="string", length = 300)
	* 
	*/
	private $description;
	
	/**
	* @ORM\OneToOne(targetEntity = "address")
	* @ORM\JoinColumn(name="address_id", referencedColumnName="id_address")
	*/
	private $address;
	
	private $select_address;
	
	private $controller_obj;
	
	function __construct($controller_obj = null) {
		$this->controller_obj = $controller_obj;
	}
	
    /**
     * Get id_location
     *
     * @return integer 
     */
    public function getIdLocation()
    {
        return $this->id_location;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return location
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return location
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set address
     *
     * @param \WebManagementBundle\Entity\address $address
     * @return location
     */
    public function setAddress(\WebManagementBundle\Entity\address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \WebManagementBundle\Entity\address 
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return location
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
     * Set select_address
     *
     * @param string $select_address
     * @return location
     */
    public function setSelectAddress($select_address)
    {
        $this->select_address = $select_address;

        return $this;
    }

    /**
     * Get select_address
     *
     * @return string 
     */
    public function getSelectAddress()
    {
        return $this->select_address;
    }


    //--------------------------------------CHOICE LIST---------------------------------------------------------------------------
    public function getChoiceList($entity, $order, $getID )
    {
		$common_query = $this->controller_obj->get('app.common_queries');    	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:".$entity, $order );
    	$option_list = NULL;
    	
    	foreach($values_list as $key=>$value)
    	{
				$option_list[$value->$getID()] = $value->getStreet();
		}
		return $option_list;
	}
//----------------------------------//----CHOICE LIST------//-----------------------------------------------------------------


//---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)	
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:location", $column, $value, 'id_location');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:location", 'id_location');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdLocation();
				$show_list[$i]['Latitude']= $table_list_query[$i]->getLatitude();
				$show_list[$i]['Longitude']= $table_list_query[$i]->getLongitude();
				$show_list[$i]['Address']= $table_list_query[$i]->getAddress()->getStreet();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Latitude']= NULL;
			$show_list[0]['Longitude'] = NULL;
			$show_list[0]['Address'] = NULL;
			$show_list[0]['Description'] = NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
    	$address_option_list = $this->getChoiceList("address", "street", "getIdAddress"); 
		$fields = array("latitude", "longitude", "select_address", "description");
		$fields_types = array("text", "text", "choice", "textarea");
		$options = ($add_options != NULL )? $add_options : array(
																NULL, 
																NULL, 
																array("choices" => $address_option_list, "placeholder" => "Select Address"), 
																NULL
																);
		$items['entity'] = "\location";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//---------------------------------------------GENERATE FORM FUNCTIONS---------------------------------------------------
	
//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new location();
		$em = $this->controller_obj->getDoctrine()->getManager();
		$new_instance->setLatitude($data_obj->getLatitude());
		$new_instance->setLongitude($data_obj->getLongitude());
		$address_repo = $em->getRepository("WebManagementBundle:address");
		$address = $address_repo->find($data_obj->getSelectAddress());
		$new_instance->setAddress($address);			
		$new_instance->setDescription($data_obj->getDescription());			
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
		$current_repo = $em->getRepository("WebManagementBundle:location");
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
		$entity_repo = $em->getRepository("WebManagementBundle:location");
		$address_option_list = $this->getChoiceList("address", "street", "getIdAddress");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getLatitude()),
						 array("data"=>$item_found->getLongitude()),
						 array("choices"=>$address_option_list, "preferred_choices"=> array($item_found->getAddress()->getIdAddress()), 'disabled'=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:location")->find($item_id);
		$item->setLatitude($data_obj->getLatitude());
		$item->setLongitude($data_obj->getLongitude());
		$address_repo = $em->getRepository("WebManagementBundle:address");
		$address = $address_repo->find($data_obj->getSelectAddress());
		$item->setAddress($address);
		$item->setDescription($data_obj->getDescription());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//----------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:location", "id_location", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Back End: </strong>".$row[0]->getIdLocation()."</div>
								<div>"."<strong> Latitude: </strong> ".$row[0]->getLatitude()."</div>
								<div>"."<strong> Longitude: </strong> ".$row[0]->getLongitude()."</div>
								<div>".'<strong> Address: </strong>'.$row[0]->getAddress()->getStreet()." ".$row[0]->getAddress()->getCity().", ".$row[0]->getAddress()->getState().". ".$row[0]->getAddress()->getCountry()." ".$row[0]->getAddress()->getZipCode()."</a></div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("latitude", "longitude", "address_id");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 

}
