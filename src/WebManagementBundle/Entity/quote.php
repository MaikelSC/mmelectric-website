<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebManagementBundle\Helpers\CommonQueries;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\quoteRepository")
* @ORM\Table(name="tb_quote")
* @UniqueEntity(fields={"description", "client_name", "phone", "email"}, message="Sorry, We have received this quote before.")
*/
class quote
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_quote;	
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 80) 
	* @Assert\NotBlank()
	*/
	private $client_name;
	
	/**
	* 
	* @ORM\Column(type= "string", length = 30) 
	* @Assert\NotBlank()
	*/	
	private $phone;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 60)
	* @Assert\NotBlank()
	*/
	private $email;
	
	/**
	* 
	* @ORM\Column(type = "datetime")
	* @Assert\NotBlank()
	*/
	private $date;
	
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	* @Assert\NotBlank()
	*/
	private $job_type;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 100000)
	* @Assert\NotBlank()
	*/
	private $description;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
		$this->date = new \DateTime("now");
	}
	
	 /**
     * Get id_quote
     *
     * @return integer 
     */
    public function getIdQuote()
    {
        return $this->id_quote;
    }

    /**
     * Set client_name
     *
     * @param string $client_name
     * @return quote
     */
    public function setClientName($client_name)
    {
        $this->client_name = $client_name;

        return $this;
    }

    /**
     * Get client_name
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->client_name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return quote
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return quote
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
     * Set date
     *
     * @param \DateTime $date
     * @return quote
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Set job_type
     *
     * @param boolean $jobType
     * @return quote
     */
    public function setJobType($jobType)
    {
        $this->job_type = $jobType;

        return $this;
    }

    /**
     * Get job_type
     *
     * @return boolean 
     */
    public function getJobType()
    {
        return $this->job_type;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return quote
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
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:quote", $column, $value, 'date');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:quote", 'date');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdQuote();
				$show_list[$i]['Date']= $table_list_query[$i]->getDate()->format('M-d-Y');
				$show_list[$i]['Client']= $table_list_query[$i]->getClientName();
				$show_list[$i]['E-mail']= $table_list_query[$i]->getEmail();
				$show_list[$i]['Job Type']= $table_list_query[$i]->getJobType()? "Commercial" : "Residential" ;
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Date']= NULL;
			$show_list[0]['Client']= NULL;
			$show_list[0]['E-mail']= NULL;
			$show_list[0]['Job Type']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTION----------------------------------------------------
    public function addElementForm($add_options = NULL)
    {  
    	$fields = array("client_name", "email", "phone", "job_type", "description");
		$fields_types = array("text", "email", "text", "choice", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(array("label"=>"Name"), NULL, NULL, array('choices'=>array(1=>"Commercial", 0=>"Residential"), 'multiple'=>false, 'expanded'=>true, 'label'=>FALSE), NULL);
		$items['entity'] = "\quote";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//------------------------------------------//---GENERATE FORM FUNCTION----//--------------------------------------------

//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		
		$new_instance = new quote();	
		$new_instance->setClientName($data_obj->getClientName());
		$new_instance->setEmail($data_obj->getEmail());
		$new_instance->setPhone($data_obj->getPhone());
		$new_instance->setJobType($data_obj->getJobType());
		$new_instance->setDate($this->getDate());
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
		$current_repo = $em->getRepository("WebManagementBundle:quote");
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
		$entity_repo = $em->getRepository("WebManagementBundle:quote");
		$item_found = $entity_repo->find($item_id);		
		$options = array(
						 array("data"=>$item_found->getClientName(), "label"=>"Name"),
						 array("data"=>$item_found->getEmail()),
						 array("data"=>$item_found->getPhone()),
						 array('choices'=>array(1=>"Commercial", 0=>"Residential"), 'preferred_choices'=>array((int)$item_found->getJobType()), 'multiple'=>false, 'expanded'=>true, 'label'=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:quote")->find($item_id);
		$item->setClientName($data_obj->getClientName());
		$item->setEmail($data_obj->getEmail());;	
		$item->setPhone($data_obj->getPhone());;	
		$item->setJobType($data_obj->getJobType());;	
		$item->setDescription($data_obj->getDescription());
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//--------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:quote", "id_quote", $id);
//		dump($row);die;
		$row_array = array();
		$row_array["content"] = 
								"<div>"."<strong> Client Name: </strong> ".$row[0]->getClientName()."</div>
								<div>"."<strong> E-mail: </strong> ".$row[0]->getEmail()."</div>
								<div>"."<strong> Phone: </strong> ".$row[0]->getPhone()."</div>
								<div>"."<strong> Date: </strong> ".$row[0]->getDate()->format('M-d-Y')."</div>
								<div>"."<strong> Job Type: </strong> ".($row[0]->getJobType()? "Commercial" : "Residential") ."</div>								
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("client_name", "email", "phone", "job_type");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
