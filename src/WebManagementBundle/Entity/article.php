<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\articleRepository")
* @ORM\Table(name="tb_article")
* @UniqueEntity(fields={"title"}, message="Sorry, this article already exist.")
* @UniqueEntity(fields={"file_attached"}, message="Sorry, there is another article using this file.")
* @UniqueEntity(fields={"content"}, message="Somebody wrote this before. Please, be creative.")
*/
class article {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_article;
	
	/**
	* 
	* @ORM\Column(type="string", length = 300)
	* 
	*/
	private $title;
	
	/**
	* 
	* @ORM\Column(type= "datetime")
	* @Assert\NotBlank()
	*/
	private $posting_date;
	
	/**
	* 
	* @ORM\ManyToOne(targetEntity = "writer")
	* @ORM\JoinColumn(name="writer_id", referencedColumnName="id_writer", onDelete="CASCADE")
	*/
	private $writer;
	/**
	* 
	* @ORM\Column(type="string", length= 1000000)
	* 
	*/
	private $content;
	
	/**
	* @ORM\OneToOne(targetEntity = "files")
	* @ORM\JoinColumn(name="files_id", referencedColumnName="id_files", onDelete="CASCADE")
	*/
	private $file_attached;
	
	/**
	* @ORM\ManyToOne(targetEntity = "category")
	* @ORM\JoinColumn(name="category_id", referencedColumnName="id_category", onDelete = "CASCADE")
	*/
	private $category;
	
	/**
	* 
	* @ORM\Column(type = "boolean")
	*/
	private $active;
	
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
	* 				maxSize = "5M",
	* 				maxSizeMessage = "Sorry, The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.",
	* 				mimeTypes = {"image/jpg", "image/jpeg", "image/png", "image/gif", "image/tiff"},
    *    			mimeTypesMessage = "Unknown image format. Try with a valid type: *.jpg, *.jpeg, *.png, *.gif, *.tiff"
	* )
	* 
	*/
	private $photo_uploaded;
	
	private $select_category;
	
	private $select_file;
	
	private $select_writer;
	
	private $select_album;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
			$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_article
     *
     * @return integer 
     */
    public function getIdArticle()
    {
        return $this->id_article;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set posting_date
     *
     * @param \DateTime $postingDate
     * @return article
     */
    public function setPostingDate($postingDate)
    {
        $this->posting_date = $postingDate;

        return $this;
    }

    /**
     * Get posting_date
     *
     * @return \DateTime 
     */
    public function getPostingDate()
    {
        return $this->posting_date;
    }

	
    /**
     * Set writer
     *
     * @param \WebManagementBundle\Entity\writer $writer
     * @return article
     */
    public function setWriter(\WebManagementBundle\Entity\writer $writer = null)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get writer
     *
     * @return \WebManagementBundle\Entity\writer 
     */
    public function getWriter()
    {
        return $this->writer;
    }
    
    /**
     * Set content
     *
     * @param string $content
     * @return article
     */
     
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set file_attached
     *
     * @param \WebManagementBundle\Entity\files $fileAttached
     * @return article
     */
    public function setFileAttached(\WebManagementBundle\Entity\files $fileAttached = null)
    {
        $this->file_attached = $fileAttached;

        return $this;
    }

    /**
     * Get file_attached
     *
     * @return \WebManagementBundle\Entity\file_attached 
     */
    public function getFileAttached()
    {
        return $this->file_attached;
    }
    
    /**
     * Set select_file
     *
     * @param string $select_file
     * @return article
     */
     
    public function setSelectFile($select_file)
    {
        $this->select_file = $select_file;

        return $this;
    }

    /**
     * Get select_file
     *
     * @return string 
     */
    public function getSelectFile()
    {
        return $this->select_file;
    }
    
    /**
     * Set select_writer
     *
     * @param string $select_writer
     * @return article
     */
     
    public function setSelectWriter($select_writer)
    {
        $this->select_writer = $select_writer;

        return $this;
    }

    /**
     * Get select_writer
     *
     * @return string 
     */
    public function getSelectWriter()
    {
        return $this->select_writer;
    }
    
    /**
     * Set category
     *
     * @param \WebManagementBundle\Entity\category $category
     * @return article
     */
    public function setCategory(\WebManagementBundle\Entity\category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \WebManagementBundle\Entity\category 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set select_category
     *
     * @param $select_category
     * @return article
     */
    public function setSelectCategory($select_category = null)
    {
        $this->select_category = $select_category;

        return $this;
    }

    /**
     * Get category
     *
     * @return category 
     */
    public function getSelectCategory()
    {
        return $this->select_category;
    }
    
    /**
     * Set active
     *
     * @param boolean $active
     * @return article
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
     * Set url_storage
     *
     * @param string $urlStorage
     * @return article
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
     * Set select_album
     *
     * @param string $select_album
     * @return article
     */
    public function setSelectAlbum($select_album)
    {
        $this->select_album = $select_album;

        return $this;
    }

    /**
     * Get select_album
     *
     * @return string 
     */
    public function getSelectAlbum()
    {
        return $this->select_album;
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
     * Set album
     *
     * @param \WebManagementBundle\Entity\album $album
     * @return article
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
		   
//----------------------------------------UPLOAD  Photo-------------------------------------------------------------------
	public function uploadPhoto($data_obj)
	{
		$url_file = "files/articles/";
		$time = date('m/d/YH:i:s');
		$file_name = $this->cleanString($data_obj->getPhotoUploaded()->getClientOriginalName().$time)."."; 
		$extension = $data_obj->getPhotoUploaded()->guessExtension();
		$file_name .= $extension ? $extension : "jpeg";
		$fs = new Filesystem();
		$data_obj->getPhotoUploaded()->move($url_file, $file_name);		
		$url_file .= $file_name;
		return $url_file;
	}
//----------------------------------//----UPLOAD FILE-----//-------------------------------------------------
	
//--------------------------------------CHOICE LIST---------------------------------------------------------------------------
    public function getChoiceList($entity, $order, $getID )
    {
		$common_query = $this->controller_obj->get('app.common_queries');    	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:".$entity, $order );
    	$option_list = NULL;
    	
    	foreach($values_list as $key=>$value)
    	{
				$option_list[$value->$getID()] = $value->getName();
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
    	
//---------------------------------------------GENERATE LIST VIEW-------------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:article", $column, $value, 'posting_date');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:article", 'posting_date');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdArticle();				
				$show_list[$i]['Posting Date']= $table_list_query[$i]->getPostingDate()->format('M-d-Y');
				$show_list[$i]['Active']= $table_list_query[$i]->getActive()? "YES": "NO";	
				$show_list[$i]['Written By']= $table_list_query[$i]->getWriter()->getName();	
				$show_list[$i]['Category']= $table_list_query[$i]->getCategory()->getName();									
				$show_list[$i]['Title']= $table_list_query[$i]->getTitle();
				$show_list[$i]['Content']= $table_list_query[$i]->getContent();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;			
			$show_list[0]['Posting Date']= NULL;
			$show_list[0]['Active']= NULL;			
			$show_list[0]['Written By']= NULL;			
			$show_list[0]['Category']= NULL;
			$show_list[0]['Title']= NULL;
			$show_list[0]['Content']= NULL;
		}
//		dump($show_list);die;
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//------------------------------------------------------

//-----------------------------------------------SELECTABLE FILES-------------------------------------------------------------

	public function SelectableFiles()
	{
		$all_files = $this->getChoiceList("files", "uploading_date", "getIdFiles");
    	$common_query = $this->controller_obj->get('app.common_queries');    	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:article", "posting_date" );
    	$file_attached_list = array();
    	foreach($values_list as $key => $value){
			if($value->getFileAttached()){
				$file_attached_list[$value->getFileAttached()->getIdFiles()] = $value->getFileAttached()->getName();
			}
		}
		return array_diff($all_files, $file_attached_list);
	}
//------------------------------------------//---SELECTABLE FILES---//--------------------------------------------------------	
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {   	
    	$files_option_list = $this->SelectableFiles();
    	$writer_option_list = $this->getChoiceList("writer", "name", "getIdWriter");
    	$category_option_list = $this->getChoiceList("category", "name", "getIdCategory");
    	$album_option_list = $this->getChoiceList("album", "name", "getIdAlbum");
		$fields = array("title","posting_date", "select_writer", "select_category", "select_file", "select_album", "photo_uploaded", "active", "content");
		$fields_types = array("text", "date", "choice", "choice", "choice", "choice", "file", "checkbox", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(
																NULL, 
																array('format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y')), "data" => new \DateTime("now")), 
																array("choices"=>$writer_option_list), 
																array("choices"=>$category_option_list), 
																array("choices"=>$files_option_list, "placeholder"=> "Select a File", "required"=>FALSE), 
																array("choices"=>$album_option_list, "placeholder"=> "Select an Album", "required"=>FALSE), 
																array("label"=>"Upload Photo", "required"=>FALSE), 
																array('data'=>true, 'required'=>FALSE), 
																NULL
																);
		$items['entity'] = "\article";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
	
//---------------------------------------------PERSIST DATA-------------------------------------------------------------------
	public function persistData($data_obj)
	{
		
		$new_instance = new article();
		$new_instance->setTitle($data_obj->getTitle());
		$new_instance->setPostingDate($data_obj->getPostingDate());
		$new_instance->setActive($data_obj->getActive());
		$new_instance->setContent($data_obj->getContent());					
		$em = $this->controller_obj->getDoctrine()->getManager();		
		$writer_repo = $em->getRepository("WebManagementBundle:writer");		
		$writer = $writer_repo->find($data_obj->getSelectWriter());
		$new_instance->setWriter($writer);
		$category_repo = $em->getRepository("WebManagementBundle:category");
		$category = $category_repo->find($data_obj->getSelectCategory());
		$new_instance->setCategory($category);
		if($data_obj->getSelectAlbum()){
			$album_repo = $em->getRepository("WebManagementBundle:album");
			$album = $album_repo->find($data_obj->getSelectAlbum());
			$new_instance->setAlbum($album);
		}
//		dump($data_obj->getPhotoUploaded());die;
		if($data_obj->getPhotoUploaded()){
			$photo_url = $this->uploadPhoto($data_obj);
			$new_instance->setUrlStorage($photo_url);
		}
		if($data_obj->getSelectFile()){
			$file_repo = $em->getRepository("WebManagementBundle:files");
			$file = $file_repo->find($data_obj->getSelectFile());
			$new_instance->setFileAttached($file);
		}		
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
		$current_repo = $em->getRepository("WebManagementBundle:article");
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
		$files_option_list = $this->SelectableFiles();
    	$writer_option_list = $this->getChoiceList("writer", "name", "getIdWriter");
    	$category_option_list = $this->getChoiceList("category", "name", "getIdCategory");
    	$album_option_list = $this->getChoiceList("album", "name", "getIdAlbum");
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:article");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getTitle(), 'disabled'=>FALSE),						 
						 array("data"=>$item_found->getPostingDate(), 'format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y'))),
						 array("choices"=>$writer_option_list, "preferred_choices"=> array($item_found->getWriter()->getIdWriter()), 'disabled'=>FALSE),						 
						 array("choices"=>$category_option_list, "preferred_choices"=> array($item_found->getCategory()->getIdCategory()), 'disabled'=>FALSE),						 
						 array("choices"=>$files_option_list, "preferred_choices"=> array($item_found->getFileAttached()?$item_found->getFileAttached()->getIdFiles():NULL), 'disabled'=>FALSE, "placeholder"=> "Select a File", "required" =>FALSE),
						 array("choices"=>$album_option_list, "preferred_choices"=> array($item_found->getAlbum()?$item_found->getAlbum()->getIdAlbum():NULL), 'disabled'=>FALSE, "placeholder"=> "Select an Album", 'required'=>FALSE),
						 array("data"=>NULL, 'disabled'=>TRUE, 'required'=>FALSE),
						 array("data"=>$item_found->getActive(), 'required'=>FALSE),
						 array("data"=>$item_found->getContent()),
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
		$item = $em->getRepository("WebManagementBundle:article")->find($item_id);
		$item->setTitle($data_obj->getTitle());			
		$item->setPostingDate($data_obj->getPostingDate());
		$item->setActive($data_obj->getActive());
		if($data_obj->getSelectFile())
		{
			$file_repo = $em->getRepository("WebManagementBundle:files");
			$file = $file_repo->find($data_obj->getSelectFile());
			$item->setFileAttached($file);
		}
		if($data_obj->getSelectAlbum()){
			$album_repo = $em->getRepository("WebManagementBundle:album");		
			$album = $album_repo->find($data_obj->getSelectAlbum());		
			$item->setAlbum($album);
		}	
		$writer_repo = $em->getRepository("WebManagementBundle:writer");		
		$writer = $writer_repo->find($data_obj->getSelectWriter());		
		$item->setWriter($writer);
		$category_repo = $em->getRepository("WebManagementBundle:category");		
		$category = $category_repo->find($data_obj->getSelectCategory());		
		$item->setCategory($category);	
			
			
		$item->setContent($data_obj->getContent());			
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//---------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:article", "id_article", $id);
		$row_array = array();
		$fl_dir = "";
		$img_dir = "";
		if($row[0]->getFileAttached()){
			$fl_dir = $this->controller_obj->get('request')->getBasePath().'/../web/'.$row[0]->getFileAttached()->getUrlStorage();
		}
		if($row[0]->getUrlStorage()){
			$img_dir = $this->controller_obj->get('request')->getBasePath().'/../web/'.$row[0]->getUrlStorage();
		}
//		dump($row[0]);die;
		$row_array["content"] = "<div>"."<strong> Id Article: </strong>".$row[0]->getIdArticle()."</div>
								<div>"."<strong> Title: </strong> ".$row[0]->getTitle()."</div>
								<div>"."<strong> Posting Date: </strong> ".$row[0]->getPostingDate()->format('M-d-Y')."</div>
								<div>"."<strong> Active: </strong> ".($row[0]->getActive()? "YES": "NO")."</div>
								<div>"."<strong> Written By: </strong> ".$row[0]->getWriter()->getName()."</div>
								<div>"."<strong> Article Category: </strong> ".$row[0]->getCategory()->getName()."</div>
								<div>".'<strong> File Attached: </strong><a href = "'.$fl_dir.'">'.$fl_dir.'</a></div>'.
								'<div>'.'<strong> From Album: </strong> '.($row[0]->getAlbum() ? $row[0]->getAlbum()->getName() : "-")."</div>".
								'<div><strong> Photo: </strong></div><div class = "img-thumb-size"><a href = "'.$img_dir.'"><img class = "img-thumbnail img-responsive" src = "'.$img_dir.'"></img></a></div>
								<div><strong> Content: </strong></div><div class = "bg-content">'.$row[0]->getContent()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("posting_date", "writer_id", "category_id", "album_id", "active");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 

}
