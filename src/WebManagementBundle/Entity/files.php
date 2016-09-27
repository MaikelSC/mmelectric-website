<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\filesRepository")
* @ORM\Table(name="tb_files")
* @UniqueEntity(fields={"name"}, message="This file already exist")
* @UniqueEntity(fields={"description"}, message="Somebody wrote this before. Please, be creative.")
*/
class files
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_files;

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
	* @Assert\File(
	* 				maxSize = "50M",
	* 				maxSizeMessage = "Sorry, The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.",
	* 				mimeTypes = {"application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/x-rar-compressed", "application/zip"},
    *    			mimeTypesMessage = "Unknown file format. Try with a valid type: *.pdf, *.doc/docx, *.xls/xlsx, *.ppt/pptx, *.rar, *.zip"
	* )
	* 
	*/
	private $file_uploaded;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
		$this->controller_obj = $controller_obj;
	}

	  /**
     * Get id_files
     *
     * @return integer 
     */
    public function getIdFiles()
    {
        return $this->id_files;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return files
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
     * @return files
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
     * @return files
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
     * @return files
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
     * @return files
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
    public function setFileUploaded($file_uploaded)
    {
        $this->file_uploaded = $file_uploaded;

        return $this;
    }

    /**
     * Get photo_uploaded
     *
     * @return file 
     */
    public function getFileUploaded()
    {
        return $this->file_uploaded;
    }
    

//----------------------------------------CLEAN STRING------------------------------------------------------------------------
	public function cleanString($string)
	{
	   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
//----------------------------------//----CLEAN STRING------//----------------------------------------------------------------
	
//----------------------------------------DELETE FILE------------------------------------------------------------------------
	public function removeFile($file_obj)
	{
		$fs = new Filesystem();
		if($fs->exists($file_obj->getUrlStorage()))
		{
			$fs->remove($file_obj->getUrlStorage());
		}
	}
//----------------------------------//----DELETE FILE------//----------------------------------------------------------------
		
//----------------------------------------UPLOAD  FILE-------------------------------------------------------------------
	public function uploadFile($data_obj)
	{
		$url_file = "files/articles/";
		$file_name = $this->cleanString($data_obj->getName()).".";
		$extension = $data_obj->getFileUploaded()->guessExtension();
		$file_name .= $extension ? $extension : "bin";
		$fs = new Filesystem();
		$data_obj->getFileUploaded()->move($url_file, $file_name);		
		$url_file .= $file_name;
		return $url_file;
	}
//----------------------------------//----UPLOAD FILE-----//-------------------------------------------------
	
//---------------------------------------------GENERATE LIST VIEW-------------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:files", $column, $value, 'tb_name');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:files", 'tb_name');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdFiles();
				$show_list[$i]['Uploaded on']= $table_list_query[$i]->getUploadingDate()->format('M-d-Y');
				$show_list[$i]['Uploaded by']= $table_list_query[$i]->getUploadedBy();
				$show_list[$i]['Name']= $table_list_query[$i]->getName();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['Uploaded on']= NULL;
			$show_list[0]['Uploaded by']= NULL;
			$show_list[0]['Name']= NULL;
			$show_list[0]['Description']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//------------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
    	
		$fields = array("name", "uploading_date", "uploaded_by", "description", "file_uploaded");
		$fields_types = array("text", "date", "text", "textarea", "file");
		$options = ($add_options != NULL )? $add_options : array(NULL,  array('format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y')), "data" => new \DateTime("now")), NULL, NULL, array("required"=>FALSE));
		$items['entity'] = "\\files";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
	
//---------------------------------------------PERSIST DATA-------------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new files();
		$new_instance->setName($this->cleanString($data_obj->getName()));
		$new_instance->setUploadingDate($data_obj->getUploadingDate());
		$new_instance->setDescription($data_obj->getDescription());					
		$new_instance->setUploadedBy($data_obj->getUploadedBy());					
		$em = $this->controller_obj->getDoctrine()->getManager();
		if($data_obj->getFileUploaded()){
			$url_file = $this->uploadFile($data_obj);
			$new_instance->setUrlStorage($url_file);	
		}
		
		$em->persist($new_instance);
		$em->flush();
	}	
//------------------------------------------//---PERSIST DATA---//-------------------------------------------------------

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
		$current_repo = $em->getRepository("WebManagementBundle:files");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$current_row = $current_repo->find($items_ids_list[$i]);
			$items_found[$i] = $current_row;
			$this->removeFile($current_row);
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
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:files");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getName(), 'disabled'=>TRUE),
						 array("data"=>$item_found->getUploadingDate(), 'format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y'))), 
						 array("data"=>$item_found->getUploadedBy(), 'disabled'=>TRUE), 
						 array("data"=>$item_found->getDescription()),
						 array("data"=>NULL, 'disabled'=>TRUE)
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
		$item = $em->getRepository("WebManagementBundle:files")->find($item_id);
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
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:files", "id_files", $id);
		$row_array = array();
		$fl_dir = NULL;
		if($row[0]->getUrlStorage()){
			$fl_dir = $this->controller_obj->get('request')->getBasePath().'/../web/'.$row[0]->getUrlStorage();
		}		
		$row_array["content"] = "<div>"."<strong> Id File: </strong>".$row[0]->getIdFiles()."</div>
								<div>"."<strong> Name: </strong> ".$row[0]->getName()."</div>
								<div>"."<strong> Uploading Date: </strong> ".$row[0]->getUploadingDate()->format('M-d-Y')."</div>
								<div>"."<strong> Uploaded By: </strong> ".$row[0]->getUploadedBy()."</div>
								<div>".'<strong> File: </strong><a href = "'.$fl_dir.'">'.$fl_dir."</a></div>
								<div><strong> Description: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------
 

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("uploading_date", "uploaded_by");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
