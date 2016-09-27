<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\albumRepository")
* @ORM\Table(name="tb_album")
* @UniqueEntity(fields={"name"}, message="Sorry, this album already exist.")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class album
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_album;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 20)
	* @Assert\NotBlank()
	*/
	private $name;
	
	/**
	* 
	* @ORM\Column(type = "datetime")
	* @Assert\NotBlank()
	*/
	private $creation_date;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 200)
	* @Assert\NotBlank()
	*/
	private $description;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 300)
	*/
	private $album_url;
	
	/**
	* 
	* @ORM\Column(type = "string", length = 50)
	* @Assert\NotBlank()
	*/
	private $created_by;
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	* @Assert\NotBlank()
	*/
	private $album_type;
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	*/
	private $visible;
	
	private $controller_obj;

	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_album
     *
     * @return integer 
     */
    public function getIdAlbum()
    {
        return $this->id_album;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return album
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
     * Set creation_date
     *
     * @param \DateTime $creationDate
     * @return album
     */
    public function setCreationDate($creationDate)
    {
        $this->creation_date = $creationDate;

        return $this;
    }

    /**
     * Get creation_date
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return album
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
     * Set album_url
     *
     * @param string $albumUrl
     * @return album
     */
    public function setAlbumUrl($albumUrl)
    {
        $this->album_url = $albumUrl;

        return $this;
    }

    /**
     * Get album_url
     *
     * @return string 
     */
    public function getAlbumUrl()
    {
        return $this->album_url;
    }

    /**
     * Set created_by
     *
     * @param string $createdBy
     * @return album
     */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get created_by
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }
    
    /**
     * Set album_type
     *
     * @param boolean $imgAlbum
     * @return album
     */
    public function setAlbumType($albumType)
    {
        $this->album_type = $albumType;

        return $this;
    }

    /**
     * Get album_type
     *
     * @return boolean 
     */
    public function getAlbumType()
    {
        return $this->album_type;
    }
    
    /**
     * Set visible
     *
     * @param boolean $visible
     * @return album
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }
//----------------------------------------CLEAN STRING--------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//------------------------------------------------------------
	    
//--------------------------------------------CREATE OR REMOVE FILES AND DIRECTORIES--------------------------------------
	public function createOrRemoveFile($data_obj, $create)
	{
		$url_album = "files/";
		$url_album .= $data_obj->getAlbumType()?"photos/":"videos/";
		$url_album .= $this->cleanString($data_obj->getName());
		$fs = new Filesystem();		
		if( $create && !($fs->exists($url_album)) )
		{
			$fs->mkdir($url_album);
		}
		else
		{
			$fs->remove($url_album);
		}
		return $url_album;
	}
//------------------------------------//------CREATE OR REMOVE FILES AND DIRECTORIES-------//-----------------------------
	
//---------------------------------------------GENERATE LIST VIEW---------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:album", $column, $value, 'name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:album", 'name');
		}		
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdAlbum();
				$show_list[$i]['Created on']= $table_list_query[$i]->getCreationDate()->format('M-d-Y');
				$show_list[$i]['Album name']= $table_list_query[$i]->getName();
				$show_list[$i]['Album type']= $table_list_query[$i]->getAlbumType()? "Photos": "Videos";
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Created on']= NULL;
			$show_list[0]['Album name']= NULL;
			$show_list[0]['Album type']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//--------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS----------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
		$fields = array("name","creation_date", "album_type", "created_by", "visible", "description");
		$fields_types = array("text", "date", "choice", "text", "checkbox", "textarea");
		$options = ($add_options != NULL )? $add_options : array(NULL, array('format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y')), "data" => new \DateTime("now")), array('choices'=>array(1=>"Photos", 0=>"Videos"), 'multiple'=>false, 'expanded'=>true, 'label'=>FALSE), NULL, array('data'=>true, 'required'=>FALSE), NULL);
		$items['entity'] = "\album";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS----------------------------------------------------
	
//---------------------------------------------PERSIST DATA---------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new album();
		$new_instance->setName($this->cleanString($data_obj->getName()));
		$new_instance->setCreationDate($data_obj->getCreationDate());
		$new_instance->setDescription($data_obj->getDescription());	
		$url_album = $this->createOrRemoveFile($data_obj, true);		
		$new_instance->setAlbumUrl($url_album);			
		$new_instance->setCreatedBy($data_obj->getCreatedBy());			
		$new_instance->setAlbumType($data_obj->getAlbumType());			
		$new_instance->setVisible($data_obj->getVisible());			
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
		$current_repo = $em->getRepository("WebManagementBundle:album");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$current_row = $current_repo->find($items_ids_list[$i]);
			$items_found[$i] = $current_row;
			$this->createOrRemoveFile($current_row, false);
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
		$entity_repo = $em->getRepository("WebManagementBundle:album");
		$item_found = $entity_repo->find($item_id);
		$common_query = $this->controller_obj->get('app.common_queries');
		$options = array(
						 array("data"=>$item_found->getName()),
						 array("data"=>$item_found->getCreationDate(), 'format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y'))),						 
						 array("choices"=> array(1=>"Photos Album", 0=>"Videos Album"), 'preferred_choices'=>array((int)$item_found->getAlbumType()) , 'multiple' => FALSE, 'expanded'=>TRUE, 'disabled'=>TRUE, 'label'=>FALSE),
						 array("data"=>$item_found->getCreatedBy()),
						 array("data"=>$item_found->getVisible(), 'required'=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:album")->find($item_id);
		$item->setName($data_obj->getName());			
		$item->setCreationDate($data_obj->getCreationDate());
		$item->setDescription($data_obj->getDescription());		
		$item->setCreatedBy($data_obj->getCreatedBy());	
		$item->setAlbumType($data_obj->getAlbumType());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//-----------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:album", "id_album", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Album: </strong>".$row[0]->getIdAlbum()."</div>
								<div>"."<strong> Name: </strong> ".$row[0]->getName()."</div>
								<div>"."<strong> Creation Date: </strong> ".$row[0]->getCreationDate()->format('M-d-Y')."</div>
								<div>"."<strong> Created By: </strong> ".$row[0]->getCreatedBy()."</div>
								<div>"."<strong> Album Url: </strong> ".$row[0]->getAlbumUrl()."</div>
								<div>"."<strong> Album Type: </strong> ".($row[0]->getAlbumType()? "Photos": "Videos")."</div>
								<div>"."<strong> Visible: </strong> ".($row[0]->getVisible()? "Yes": "No")."</div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------    

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("creation_date", "album_type", "created_by", "visible");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
 
}
