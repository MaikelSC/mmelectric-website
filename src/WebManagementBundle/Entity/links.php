<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\linksRepository")
* @ORM\Table(name="tb_links")
* @UniqueEntity(fields={"title"}, message="Sorry, this article already exist.")
* @UniqueEntity(fields={"url_links"}, message="Somebody wrote this link before. Please, be creative.")
*/
class links {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_links;
	
	/**
	* 
	* @ORM\Column(type="string", length = 300)
	* 
	*/
	private $title;
	
	/**
	* 
	* @ORM\Column(type= "string" , length = 300)
	*/
	private $url_links;
	
	/**
	* 
	* @ORM\Column(type="string", length= 1000000)
	* 
	*/
	private $description;
	
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
		
	private $select_category;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
			$this->controller_obj = $controller_obj;
	}
	
	
    /**
     * Get id_links
     *
     * @return integer 
     */
    public function getIdLinks()
    {
        return $this->id_links;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return links
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
     * Set url_links
     *
     * @param string $urlLinks
     * @return links
     */
    public function setUrlLinks($urlLinks)
    {
        $this->url_links = $urlLinks;

        return $this;
    }

    /**
     * Get url_links
     *
     * @return string 
     */
    public function getUrlLinks()
    {
        return $this->url_links;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return links
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
     * Set active
     *
     * @param boolean $active
     * @return links
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
     * Set category
     *
     * @param \WebManagementBundle\Entity\category $category
     * @return links
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
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:links", $column, $value, 'title');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:links", 'title');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdLinks();
				$show_list[$i]['Active']= $table_list_query[$i]->getActive()? "YES": "NO";	
				$show_list[$i]['Category']= $table_list_query[$i]->getCategory()->getName();									
				$show_list[$i]['Title']= $table_list_query[$i]->getTitle();
				$show_list[$i]['Description']= $table_list_query[$i]->getDescription();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;			
			$show_list[0]['Active']= NULL;					
			$show_list[0]['Category']= NULL;
			$show_list[0]['Title']= NULL;
			$show_list[0]['Description']= NULL;
		}
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//------------------------------------------------------

//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {   
    	$category_option_list = $this->getChoiceList("category", "name", "getIdCategory");
		$fields = array("title", "url_links", "select_category", "active", "description");
		$fields_types = array("text", "url", "choice", "checkbox", "textarea");
		$options = ($add_options != NULL )? $add_options : array(
																NULL, 
																NULL,
																array("choices"=>$category_option_list),
																array('data'=>true, 'required'=>FALSE), 
																NULL
																);
		$items['entity'] = "\links";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
	
//---------------------------------------------PERSIST DATA-------------------------------------------------------------------
	public function persistData($data_obj)
	{
		
		$new_instance = new links();
		$new_instance->setTitle($data_obj->getTitle());
		$new_instance->setUrlLinks($data_obj->getUrlLinks());
		$new_instance->setActive($data_obj->getActive());
		$new_instance->setDescription($data_obj->getDescription());					
		$em = $this->controller_obj->getDoctrine()->getManager();
		$category_repo = $em->getRepository("WebManagementBundle:category");
		$category = $category_repo->find($data_obj->getSelectCategory());
		$new_instance->setCategory($category);
			
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
		$current_repo = $em->getRepository("WebManagementBundle:links");
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
//------------------------------------------//---REMOVE ITEM---//-------------------------------------------------------------
	
//------------------------------------------//-----UPDATE ITEM FORM----//-----------------------------------------------------
	 public function updateItemForm($item_id)
    {
    	$category_option_list = $this->getChoiceList("category", "name", "getIdCategory");
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:links");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getTitle(), 'disabled'=>FALSE),						 
						 array("data"=>$item_found->getUrlLinks(), 'disabled'=>FALSE),						 
						 array("choices"=>$category_option_list, "preferred_choices"=> array($item_found->getCategory()->getIdCategory()), 'disabled'=>FALSE),
						 array("data"=>$item_found->getActive(), 'required'=>FALSE),
						 array("data"=>$item_found->getDescription()),
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
		$item = $em->getRepository("WebManagementBundle:links")->find($item_id);
		$item->setTitle($data_obj->getTitle());	
		$item->setUrlLinks($data_obj->getUrlLinks());	
		$item->setActive($data_obj->getActive());		
		$category_repo = $em->getRepository("WebManagementBundle:category");		
		$category = $category_repo->find($data_obj->getSelectCategory());		
		$item->setCategory($category);				
		$item->setDescription($data_obj->getDescription());			
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//---------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:links", "id_links", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Article: </strong>".$row[0]->getIdLinks()."</div>
								<div>"."<strong> Title: </strong> ".$row[0]->getTitle()."</div>
								<div>"."<strong> Url Link: </strong> <a href = '".$row[0]->getUrlLinks()."'>".$row[0]->getUrlLinks()."</a></div>
								<div>"."<strong> Active: </strong> ".($row[0]->getActive()? "YES": "NO")."</div>
								<div>"."<strong> Article Category: </strong> ".$row[0]->getCategory()->getName()."</div>
								<div><strong> Content: </strong></div><div class = 'bg-content'>".$row[0]->getDescription()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------



//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("category_id", "active");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
