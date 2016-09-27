<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WebManagementBundle\Helpers\CommonQueries;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\contactRepository")
* @ORM\Table(name="tb_contact")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class contact
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_contact;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 20)
	* @Assert\NotBlank()
	*/
	private $first_name;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 40)
	*/
	private $last_name;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $fb_url;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $twr_url;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $inst_url;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $web_url;
		
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $url_storage;
	
	/**
	* @Assert\File(
	* 				maxSize = "500K",
	* 				maxSizeMessage = "Sorry, The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.",
	* 				mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif", "image/tiff"},
    *    			mimeTypesMessage = "Unknown image format. Try with a valid type: *.jpg, *.jpeg, *.png, *.gif, *.tiff"
	* )
	* 
	*/
	private $photo_uploaded;
	
	/**
	* 
	* @ORM\ManyToOne(targetEntity = "address")
	* @ORM\JoinColumn(name="address_id", referencedColumnName="id_address", onDelete="CASCADE")
	*/
	private $address;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 100000)
	* 
	*/
	private $description;
	
	
	private $select_address;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_contacts
     *
     * @return integer 
     */
    public function getIdContact()
    {
        return $this->id_contact;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return contact
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return contact
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
     * Set fb_url
     *
     * @param string $fbUrl
     * @return contact
     */
    public function setFbUrl($fbUrl)
    {
        $this->fb_url = $fbUrl;

        return $this;
    }

    /**
     * Get fb_url
     *
     * @return string 
     */
    public function getFbUrl()
    {
        return $this->fb_url;
    }

    /**
     * Set twr_url
     *
     * @param string $twrUrl
     * @return contact
     */
    public function setTwrUrl($twrUrl)
    {
        $this->twr_url = $twrUrl;

        return $this;
    }

    /**
     * Get twr_url
     *
     * @return string 
     */
    public function getTwrUrl()
    {
        return $this->twr_url;
    }

    /**
     * Set inst_url
     *
     * @param string $instUrl
     * @return contact
     */
    public function setInstUrl($instUrl)
    {
        $this->inst_url = $instUrl;

        return $this;
    }

    /**
     * Get inst_url
     *
     * @return string 
     */
    public function getInstUrl()
    {
        return $this->inst_url;
    }

    /**
     * Set web_url
     *
     * @param string $webUrl
     * @return contact
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
     * Set url_storage
     *
     * @param string $urlStorage
     * @return photo
     */
    public function setUrlStorage($urlStorage)
    {
        $this->url_storage = $urlStorage;

        return $this;
    }

    /**
     * Get url_storage
     *
     * @return string 
     */
    public function getUrlStorage()
    {
        return $this->url_storage;
    }
    
    /**
     * Set photo_uploaded
     *
     * @param string $photo_uploaded
     * @return photo
     */
    public function setPhotoUploaded($photo_uploaded)
    {
        $this->photo_uploaded = $photo_uploaded;

        return $this;
    }

    /**
     * Get photo_uploaded
     *
     * @return file 
     */
    public function getPhotoUploaded()
    {
        return $this->photo_uploaded;
    }
    
    /**
     * Set address
     *
     * @param \WebManagementBundle\Entity\address $address
     * @return contact
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
     * @return contact
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
     * @return contact
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

//----------------------------------------CLEAN STRING------------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//----------------------------------------------------------------
	
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

//--------------------------------------------GET CONTACT PHOTOS DIRECTORY--------------------------------------
	public function getContactDirectory()
	{
		$url_album = "files/photos/contacts";
		$fs = new Filesystem();		
		if(!($fs->exists($url_album)) )
		{
			$fs->mkdir($url_album);
		}
		/*else
		{
			$fs->remove($url_album);
		}*/
		return $url_album;
	}
//------------------------------------//------GET CONTACT PHOTOS DIRECTORY-------//-----------------------------
	
//----------------------------------------DELETE FILE------------------------------------------------------------------------
	public function removePhoto($photo_obj)
	{
		$fs = new Filesystem();
		if($fs->exists($photo_obj->getUrlStorage()))
		{
			$fs->remove($photo_obj->getUrlStorage());
		}
	}
//----------------------------------//----DELETE FILE------//----------------------------------------------------------------
		
//----------------------------------------UPLOAD  FILE-------------------------------------------------------------------
	public function uploadPhoto($data_obj, $selected_album, $updating)
	{
		
		$url_photo = $selected_album."/";
		$photo_name = $data_obj->getPhotoUploaded()->getClientOriginalName();
		$fs = new Filesystem();
		if($fs->exists($url_photo.$photo_name)){
			if($updating){
				$fs->remove($url_photo.$photo_name);
			}
			else{
				$prefix = getdate()[0];
				$photo_name= $prefix.$photo_name;
			}
		}
		$data_obj->getPhotoUploaded()->move($url_photo, $photo_name);		
		$url_photo .= $photo_name;
		return $url_photo;
	}
//----------------------------------//----UPLOAD FILE-----//-------------------------------------------------
    
//---------------------------------------------GENERATE LIST VIEW--------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:contact", $column, $value, 'first_name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:contact", 'first_name');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdContact();
				$show_list[$i]['Contact name']= $table_list_query[$i]->getFirstName().' '. $table_list_query[$i]->getLastName();
				$show_list[$i]['Email']= $table_list_query[$i]->getAddress()->getEmail();
				$show_list[$i]['Address']= $table_list_query[$i]->getAddress()->getStreet();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Contact name']= NULL;
			$show_list[0]['Email']= NULL;
			$show_list[0]['Address']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//-------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTION----------------------------------------------------
    public function addElementForm($add_options = NULL)
    { 
    	$address_option_list = $this->getChoiceList("address", "street", "getIdAddress"); 
    	$fields = array("first_name","last_name", "fb_url", "twr_url", "inst_url", "web_url", "select_address", "photo_uploaded", "description");
		$fields_types = array("text", "text", "url", "url", "url", "url", "choice", "file", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(
																NULL, 
																NULL, 
																array("required"=>FALSE), 
																array("required"=>FALSE), 
																array("required"=>FALSE), 
																array("required"=>FALSE),																
																array("choices"=>$address_option_list, "placeholder" => "Select Address"),
																array("required"=>FALSE), 
																NULL
																);
		$items['entity'] = "\contact";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//------------------------------------------//---GENERATE FORM FUNCTION----//--------------------------------------------

//---------------------------------------------PERSIST DATA--------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new contact();
		$em = $this->controller_obj->getDoctrine()->getManager();
		$new_instance->setFirstName($data_obj->getFirstName());
		$new_instance->setLastName($data_obj->getLastName());		
		$new_instance->setFbUrl($data_obj->getFbUrl());
		$new_instance->setTwrUrl($data_obj->getTwrUrl());
		$new_instance->setInstUrl($data_obj->getInstUrl());
		$new_instance->setWebUrl($data_obj->getWebUrl());
		$address_repo = $em->getRepository("WebManagementBundle:address");
		$address = $address_repo->find($data_obj->getSelectAddress());
		$new_instance->setAddress($address);
		if($data_obj->getPhotoUploaded()){
			$url_photo = $this->uploadPhoto($data_obj, $this->getContactDirectory(), FALSE);
			$new_instance->setUrlStorage($url_photo);
		}		
		$new_instance->setDescription($data_obj->getDescription());				
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
		$current_repo = $em->getRepository("WebManagementBundle:contact");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$current_row = $current_repo->find($items_ids_list[$i]);
			$items_found[$i] = $current_row;
			$this->removePhoto($current_row);
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
		$entity_repo = $em->getRepository("WebManagementBundle:contact");
		$address_option_list = $this->getChoiceList("address", "street", "getIdAddress");
		$item_found = $entity_repo->find($item_id);		
		$options = array(
						 array("data"=>$item_found->getFirstName()),						 
						 array("data"=>$item_found->getLastName()),					 						 
						 array("data"=>$item_found->getFbUrl()),
						 array("data"=>$item_found->getTwrUrl()),
						 array("data"=>$item_found->getInstUrl()),
						 array("data"=>$item_found->getWebUrl()),
						 array("choices"=>$address_option_list, "preferred_choices"=> array($item_found->getAddress()->getIdAddress()), 'disabled'=>FALSE),
						 array("data"=>$item_found->getUrlStorage(), 'data_class' => null, "required"=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:contact")->find($item_id);
		$address_repo = $em->getRepository("WebManagementBundle:address");
		$address = $address_repo->find($data_obj->getSelectAddress());
		$item->setAddress($address);
		$item->setFirstName($data_obj->getFirstName());
		$item->setLastName($data_obj->getLastName());			
		$item->setFbUrl($data_obj->getFbUrl());
		$item->setTwrUrl($data_obj->getTwrUrl());
		$item->setInstUrl($data_obj->getInstUrl());
		$item->setWebUrl($data_obj->getWebUrl());		
		$item->setDescription($data_obj->getDescription());
		if($data_obj->getPhotoUploaded()){
			$url_photo = $this->uploadPhoto($data_obj, $this->getContactDirectory(), TRUE);
			$item->setUrlStorage($url_photo);
		}		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//--------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:contact", "id_contact", $id);
		$img_dir = $this->controller_obj->get('request')->getBasePath().'/../web/'.$row[0]->getUrlStorage();
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Contact: </strong>".$row[0]->getIdContact()."</div>
								<div>"."<strong> First Name: </strong> ".$row[0]->getFirstName()."</div>
								<div>"."<strong> Last Name: </strong> ".$row[0]->getLastName()."</div>
								<div>"."<strong> Email: </strong> ".$row[0]->getAddress()->getEmail()."</div>
								<div>".'<strong> Url Facebook: </strong><a href = "'.$row[0]->getFbUrl().'">'.$row[0]->getFbUrl()."</a></div>
								<div>".'<strong> Url Twitter: </strong><a href = "'.$row[0]->getTwrUrl().'">'.$row[0]->getTwrUrl()."</a></div>
								<div>".'<strong> Url Instagram: </strong><a href = "'.$row[0]->getInstUrl().'">'.$row[0]->getInstUrl()."</a></div>
								<div>".'<strong> Url Web Site: </strong><a href = "'.$row[0]->getWebUrl().'">'.$row[0]->getWebUrl()."</a></div>
								<div>".'<strong> Address: </strong>'.$row[0]->getAddress()->getStreet()." ".$row[0]->getAddress()->getCity().", ".$row[0]->getAddress()->getState().". ".$row[0]->getAddress()->getCountry()." ".$row[0]->getAddress()->getZipCode()."</a></div>
								<div>".'<strong> Picture: </strong></div><div class = "img-thumb-size"><a href = "'.$img_dir.'"><img class = "img-thumbnail img-responsive" src = "'.$img_dir.'"></img></a></div>'.
								"<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------


//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("first_name","last_name", "address_id");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
