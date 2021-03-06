<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebManagementBundle\Helpers\CommonQueries;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\addressRepository")
* @ORM\Table(name="tb_address")
* @UniqueEntity(fields={"email"}, message="Sorry, this email already exist.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class address
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_address;
	
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 80) 
	* @Assert\NotBlank()
	*/
	private $street;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 50) 
	* @Assert\NotBlank()
	*/
	private $city;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 50) 
	* @Assert\NotBlank()
	*/
	private $state;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 50) 
	* @Assert\NotBlank()
	*/
	private $country;
	
	/**
	* 
	* @ORM\Column(type= "string", length = 15) 
	* @Assert\NotBlank()
	*/
	private $zip_code;
	
	/**
	* 
	* @ORM\Column(type= "string", length = 30, unique = true) 
	*/	
	private $cellphone;
	
	/**
	* 
	* @ORM\Column(type= "string", length = 30) 
	*/	
	private $house_phone;
	
	/**
	* 
	* @ORM\Column(type= "string", length = 30) 
	*/	
	private $work_phone;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 60)
	* @Assert\NotBlank()
	*/
	private $email;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 100000)
	* 
	*/
	private $description;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
	}

    
    /**
     * Get id_address
     *
     * @return integer 
     */
    public function getIdAddress()
    {
        return $this->id_address;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return address
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return address
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
     * Set zip_code
     *
     * @param string $zipCode
     * @return address
     */
    public function setZipCode($zipCode)
    {
        $this->zip_code = $zipCode;

        return $this;
    }

    /**
     * Get zip_code
     *
     * @return string 
     */
    public function getZipCode()
    {
        return $this->zip_code;
    }

    /**
     * Set cellphone
     *
     * @param string $cellphone
     * @return address
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;

        return $this;
    }

    /**
     * Get cellphone
     *
     * @return string 
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * Set house_phone
     *
     * @param string $housePhone
     * @return address
     */
    public function setHousePhone($housePhone)
    {
        $this->house_phone = $housePhone;

        return $this;
    }

    /**
     * Get house_phone
     *
     * @return string 
     */
    public function getHousePhone()
    {
        return $this->house_phone;
    }

    /**
     * Set work_phone
     *
     * @param string $workPhone
     * @return address
     */
    public function setWorkPhone($workPhone)
    {
        $this->work_phone = $workPhone;

        return $this;
    }

    /**
     * Get work_phone
     *
     * @return string 
     */
    public function getWorkPhone()
    {
        return $this->work_phone;
    }

 /**
     * Set email
     *
     * @param string $email
     * @return address
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return address
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

//----------------------------------------CLEAN STRING------------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//----------------------------------------------------------------

//---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:address", $column, $value, 'street');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:address", 'street');
		}
		
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdAddress();
				$show_list[$i]['Street']= $table_list_query[$i]->getStreet();
				$show_list[$i]['City']= $table_list_query[$i]->getCity();
				$show_list[$i]['State']= $table_list_query[$i]->getState();
				$show_list[$i]['Country']= $table_list_query[$i]->getCountry();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Street']= NULL;
			$show_list[0]['City']= NULL;
			$show_list[0]['State']= NULL;
			$show_list[0]['Country']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTION----------------------------------------------------
    public function addElementForm($add_options = NULL)
    {  
    	$fields = array("street", "city", "state", "country", "zip_code", "cellphone", "house_phone", "work_phone", "email", "description");
		$fields_types = array("text", "text", "text", "text", "text", "text", "text", "text", "email", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(NULL, NULL, NULL, NULL,  NULL, array("required"=>FALSE), array("required"=>FALSE), array("required"=>FALSE), NULL, NULL);
		$items['entity'] = "\address";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//------------------------------------------//---GENERATE FORM FUNCTION----//--------------------------------------------

//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new address();	
		$new_instance->setStreet($data_obj->getStreet());
		$new_instance->setCity($data_obj->getCity());
		$new_instance->setState($data_obj->getState());
		$new_instance->setCountry($data_obj->getCountry());
		$new_instance->setZipCode($data_obj->getZipCode());
		$new_instance->setCellphone($data_obj->getCellphone());
		$new_instance->setHousePhone($data_obj->getHousePhone());
		$new_instance->setWorkPhone($data_obj->getWorkPhone());
		$new_instance->setEmail($data_obj->getEmail());	
		$new_instance->setDescription($data_obj->getDescription());
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
		$current_repo = $em->getRepository("WebManagementBundle:address");
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
//------------------------------------------//---REMOVE ITEM---//--------------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM FORM--------------------------------------------------------
    public function updateItemForm($item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:address");
		$item_found = $entity_repo->find($item_id);		
		$options = array(
						 array("data"=>$item_found->getStreet()),
						 array("data"=>$item_found->getCity()),
						 array("data"=>$item_found->getState()),
						 array("data"=>$item_found->getCountry()),
						 array("data"=>$item_found->getZipCode()),
						 array("data"=>$item_found->getCellphone(), "required"=>FALSE),
						 array("data"=>$item_found->getHousePhone(), "required"=>FALSE),
						 array("data"=>$item_found->getWorkPhone(), "required"=>FALSE),
						 array("data"=>$item_found->getEmail()),
						 array("data"=>$item_found->getDescription())
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
		$item = $em->getRepository("WebManagementBundle:address")->find($item_id);
		$item->setStreet($data_obj->getStreet());
		$item->setCity($data_obj->getCity());
		$item->setState($data_obj->getState());
		$item->setCountry($data_obj->getCountry());
		$item->setZipCode($data_obj->getZipCode());
		$item->setCellphone($data_obj->getCellphone());
		$item->setHousePhone($data_obj->getHousePhone());
		$item->setWorkPhone($data_obj->getWorkPhone());	
		$item->setEmail($data_obj->getEmail());	
		$item->setDescription($data_obj->getDescription());
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//--------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:address", "id_address", $id);
//		dump($row);die;
		$row_array = array();
		$row_array["content"] = 
								"<div>"."<strong> Street: </strong> ".$row[0]->getStreet()."</div>
								<div>"."<strong> City: </strong> ".$row[0]->getCity()."</div>
								<div>"."<strong> State: </strong> ".$row[0]->getState()."</div>
								<div>"."<strong> Country: </strong> ".$row[0]->getCountry()."</div>
								<div>"."<strong> Zip Code: </strong> ".$row[0]->getZipCode()."</div>
								<div>"."<strong> Cellphone: </strong> ".$row[0]->getCellphone()."</div>
								<div>"."<strong> House Phone: </strong> ".$row[0]->getHousePhone()."</div>
								<div>"."<strong> Work Phone: </strong> ".$row[0]->getWorkPhone()."</div>
								<div>"."<strong> Email: </strong> ".$row[0]->getEmail()."</div>								
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("street", "city", "state", "country", "zip_code", "cellphone", "house_phone", "work_phone");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
