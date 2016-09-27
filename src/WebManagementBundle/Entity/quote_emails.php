<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebManagementBundle\Helpers\CommonQueries;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\quote_emailsRepository")
* @ORM\Table(name="tb_quote_emails")
* @UniqueEntity(fields={"email"}, message="Sorry, this email already exist.")
*/
class quote_emails
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_quoteEmails;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 60)
	* @Assert\NotBlank()
	*/
	private $email;
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	*/
	private $active;
	
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
     * Get id_quoteEmails
     *
     * @return integer 
     */
    public function getIdQuoteEmails()
    {
        return $this->id_quoteEmails;
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
     * Set active
     *
     * @param boolean $active
     * @return quoteEmails
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
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:quote_emails", $column, $value, 'email');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:quote_emails", 'email');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdquoteEmails();
				$show_list[$i]['Active']= $table_list_query[$i]->getActive()? "YES": "NO";
				$show_list[$i]['Email']= $table_list_query[$i]->getEmail();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Active']= NULL;
			$show_list[0]['Email']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTION----------------------------------------------------
    public function addElementForm($add_options = NULL)
    {  
    	$fields = array("email", "active", "description");
		$fields_types = array("email", "checkbox", "textarea");
		$options = ($add_options != NULL )? $add_options : array(NULL, array('data'=>true, 'required'=>FALSE), NULL);
		$items['entity'] = "\quote_emails";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//------------------------------------------//---GENERATE FORM FUNCTION----//--------------------------------------------

//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new quote_emails();
		$new_instance->setEmail($data_obj->getEmail());	
		$new_instance->setDescription($data_obj->getDescription());
		$new_instance->setActive($data_obj->getActive());
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
		$current_repo = $em->getRepository("WebManagementBundle:quote_emails");
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
		$entity_repo = $em->getRepository("WebManagementBundle:quote_emails");
		$item_found = $entity_repo->find($item_id);		
		$options = array(
						 array("data"=>$item_found->getEmail()),
						 array("data"=>$item_found->getActive(), 'required'=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:quote_emails")->find($item_id);
		$item->setEmail($data_obj->getEmail());	
		$item->setActive($data_obj->getActive());
		$item->setDescription($data_obj->getDescription());
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//--------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:quote_emails", "id_quoteEmails", $id);
//		dump($row);die;
		$row_array = array();
		$row_array["content"] = 
								"<div>"."<strong> Email: </strong> ".$row[0]->getEmail()."</div>
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
