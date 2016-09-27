<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\videoRepository")
* @ORM\Table(name="tb_video")
* @UniqueEntity(fields={"name"}, message="This video already exist")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class video
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_video;

	/**
	* 
	* @ORM\Column(type= "string" , length = 20)
	* @Assert\NotBlank()
	*/
	private $name;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 50)
	* @Assert\NotBlank()
	*/
	private $uploaded_by;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $description;
	
	/**
	* 
	* @ORM\Column(type= "datetime")
	* @Assert\NotBlank()
	*/
	private $uploading_date;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $url_storage;
	
	/**
	* 
	* @ORM\ManyToOne(targetEntity = "album")
	* @ORM\JoinColumn(name="album_id", referencedColumnName="id_album", onDelete="CASCADE")
	*/
	private $album;
	
	/**
	* @Assert\File(
	* 				maxSize = "250M",
	* 				maxSizeMessage = "Sorry, The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.",
	* 				mimeTypes = {"video/mp4", "video/ogg", "video/webm"},
    *    			mimeTypesMessage = "Unknown image format. Try with a valid type: *.mp4, *.ogg, *.webm"
	* )
	* @Assert\NotBlank() 
	* 
	*/
	private $video_uploaded;
	
	private $select_album;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_video
     *
     * @return integer 
     */
    public function getIdVideo()
    {
        return $this->id_video;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return photo
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
     * Set uploaded_by
     *
     * @param string $uploadedBy
     * @return photo
     */
    public function setUploadedBy($uploadedBy)
    {
        $this->uploaded_by = $uploadedBy;

        return $this;
    }

    /**
     * Get uploaded_by
     *
     * @return string 
     */
    public function getUploadedBy()
    {
        return $this->uploaded_by;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return photo
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
     * Set uploading_date
     *
     * @param \DateTime $uploadingDate
     * @return photo
     */
    public function setUploadingDate($uploadingDate)
    {
        $this->uploading_date = $uploadingDate;

        return $this;
    }

    /**
     * Get uploading_date
     *
     * @return \DateTime 
     */
    public function getUploadingDate()
    {
        return $this->uploading_date;
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
    public function setVideoUploaded($video_uploaded)
    {
        $this->video_uploaded = $video_uploaded;

        return $this;
    }

    /**
     * Get photo_uploaded
     *
     * @return file 
     */
    public function getVideoUploaded()
    {
        return $this->video_uploaded;
    }

    /**
     * Set album
     *
     * @param \WebManagementBundle\Entity\album $album
     * @return photo
     */
    public function setAlbum(\WebManagementBundle\Entity\album $album = null)
    {
        $this->album = $album;

        return $this;
    }

    /**
     * Get album
     *
     * @return \WebManagementBundle\Entity\album 
     */
    public function getAlbum()
    {
        return $this->album;
    }
    /**
     * Set Select_album
     *
     * @param int select_album
     * @return photo
     */
    public function setSelectAlbum($select_album )
    {
        $this->select_album = $select_album;

        return $this;
    }

    /**
     * Get Select_album
     *
     * @return id_album 
     */
    public function getSelectAlbum()
    {
        return $this->select_album;
    }
    
//--------------------------------------CHOICE LIST---------------------------------------------------------------------------
    public function getChoiceList()
    {
		$common_query = $this->controller_obj->get('app.common_queries');    	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:album", "id_album" );
    	$option_list = NULL;
    	
    	foreach($values_list as $key=>$value)
    	{
    		if(!$value->getAlbumType())
    		{
				$option_list[$value->getIdAlbum()] = $value->getName();
			}
		}
		return $option_list;
	}
//----------------------------------//----CHOICE LIST------//-----------------------------------------------------------------
	
//----------------------------------------CLEAN STRING------------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//----------------------------------------------------------------
	
//----------------------------------------DELETE FILE------------------------------------------------------------------------
	public function removeVideo($video_obj)
	{
		$fs = new Filesystem();
		if($fs->exists($video_obj->getUrlStorage()))
		{
			$fs->remove($video_obj->getUrlStorage());
		}
	}
//----------------------------------//----DELETE FILE------//----------------------------------------------------------------
		
//----------------------------------------UPLOAD FILE-------------------------------------------------------------------
	public function uploadVideo($data_obj, $selected_album)
	{
		$url_video = $selected_album->getAlbumUrl()."/";
		$video_name = $this->cleanString($data_obj->getName()).".";
		$extension = $data_obj->getVideoUploaded()->guessExtension();
		$video_name .= $extension ? $extension : "mp4";
		$fs = new Filesystem();
		$data_obj->getVideoUploaded()->move($url_video, $video_name);		
		$url_video .= $video_name;
		return $url_video;
	}
//----------------------------------//----UPLOAD FILE-----//-------------------------------------------------
	
//---------------------------------------------GENERATE LIST VIEW-------------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:video", $column, $value, 'name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:video", 'name');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdVideo();
				$show_list[$i]['Uploaded on']= $table_list_query[$i]->getUploadingDate()->format('M-d-Y');
				$show_list[$i]['Uploaded by']= $table_list_query[$i]->getUploadedBy();
				$show_list[$i]['Album']= $table_list_query[$i]->getAlbum()->getName();
				$show_list[$i]['Name']= $table_list_query[$i]->getName();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Uploaded on']= NULL;
			$show_list[0]['Uploaded by']= NULL;
			$show_list[0]['Album']= NULL;
			$show_list[0]['Name']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//------------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
    	/*dump(date("MMMM dd yyyy"));
    	die;*/
    	$option_list = $this->getChoiceList();
		$fields = array("name","select_album", "uploading_date", "uploaded_by", "video_uploaded", "description");
		$fields_types = array("text", "choice", "date", "text", "file", "textarea");
		$options = ($add_options != NULL )? $add_options : array(NULL, array("choices"=>$option_list), array('format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y')), "data" => new \DateTime("now")), NULL, NULL, NULL);
		$items['entity'] = "\\video";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
	
//---------------------------------------------PERSIST DATA-------------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new video();
		$new_instance->setName($this->cleanString($data_obj->getName()));
		$new_instance->setUploadingDate($data_obj->getUploadingDate());
		$new_instance->setDescription($data_obj->getDescription());					
		$new_instance->setUploadedBy($data_obj->getUploadedBy());					
		$em = $this->controller_obj->getDoctrine()->getManager();
		$current_repo = $em->getRepository("WebManagementBundle:album");
		$selected_album = $current_repo->find($data_obj->getSelectAlbum());
		$url_video = $this->uploadVideo($data_obj, $selected_album);
		$new_instance->setAlbum($selected_album);
		$new_instance->setUrlStorage($url_video);	
		$em->persist($new_instance);
		$em->flush();
	}	
//------------------------------------------//---PERSIST USER DATA---//-------------------------------------------------------

//---------------------------------------------REMOVE ITEM--------------------------------------------------------------------
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
		$current_repo = $em->getRepository("WebManagementBundle:video");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$current_row = $current_repo->find($items_ids_list[$i]);
			$items_found[$i] = $current_row;
			$this->removeVideo($current_row);
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
//------------------------------------------//---REMOVE ITEM---//-------------------------------------------------------------
	
//------------------------------------------//-----UPDATE ITEM FORM----//-----------------------------------------------------
	 public function updateItemForm($item_id)
    {
		$option_list = $this->getChoiceList();
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:video");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getName(), 'disabled'=>TRUE),
						 array("choices"=>$option_list, "preferred_choices"=> array($item_found->getAlbum()->getIdAlbum()), 'disabled'=>TRUE),
						 array("data"=>$item_found->getUploadingDate(), 'format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y'))), 
						 array("data"=>$item_found->getUploadedBy(), 'disabled'=>TRUE),
						 array("data"=>NULL, 'disabled'=>TRUE), 
						 array("data"=>$item_found->getDescription())
						);
		$form_array['items'] = $this->addElementForm($options);
		$form_array['instance'] = $item_found;
		return $form_array;
	}
//------------------------------------------//-----UPDATE ITEM FORM----//-----------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM ACTION-----------------------------------------------------------
    public function updateData($data_obj, $item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$item = $em->getRepository("WebManagementBundle:video")->find($item_id);
		$item->setName($data_obj->getName());			
		$item->setUploadingDate($data_obj->getUploadingDate());
		$item->setDescription($data_obj->getDescription());		
		$item->setUploadedBy($data_obj->getUploadedBy());		
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//---------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:video", "id_video", $id);
		$row_array = array();
		$vid_dir = $this->controller_obj->get('request')->getBasePath().'/../web/'.$row[0]->getUrlStorage();
		$row_array["content"] = "<div>"."<strong> Id Video: </strong>".$row[0]->getIdVideo()."</div>
								<div>"."<strong> Name: </strong> ".$row[0]->getName()."</div>
								<div>"."<strong> Uploading Date: </strong> ".$row[0]->getUploadingDate()->format('M-d-Y')."</div>
								<div>"."<strong> Uploaded By: </strong> ".$row[0]->getUploadedBy()."</div>
								<div>".'<strong> Video: </strong></div><div class = "vid-thumb-size img-thumbnail"><video controls><source src = "'.$vid_dir.'" type="video/mp4">'."</video></div>
								<div>".'<strong> From Album: </strong>'.$row[0]->getAlbum()->getName()."</div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("album_id", "uploading_date", "uploaded_by");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
