<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\postRepository")
* @ORM\Table(name="tb_post")
* @UniqueEntity(fields={"content"}, message="Somebody wrote this before. Please, be creative.")
*/
class post {
	/**
	* 
	* @ORM\Column(type= "integer") 
	* @ORM\Id
	* @ORM\GeneratedValue(strategy = "AUTO")
	*/
	private $id_post;
	
	/**
	* @ORM\Column(type="string", length = 15)
	* @Assert\NotBlank()
	*/
	private $username;
	
	/**
	* @ORM\Column(type = "string", length = 60)
	* @Assert\NotBlank()
	*/
	private $email;
	
	/**
	* 
	* @ORM\Column(type= "datetime")
	* @Assert\NotBlank()
	*/
	private $posting_date;
	
	/**
	* 
	* @ORM\Column(type="string", length= 10000)
	* @Assert\NotBlank()
	*/
	private $content; 
	
	/**
	* 
	* @ORM\ManyToOne(targetEntity = "blog")
	* @ORM\JoinColumn(name="blog_id", referencedColumnName = "id_blog", onDelete="CASCADE")
	*/
	private $blog;
	
	private $select_blog;
	
	private $controller_obj;
	
	function __construct($controller_obj = NULL)
	{
			$this->controller_obj = $controller_obj;
	}

    /**
     * Get id_post
     *
     * @return integer 
     */
    public function getIdPost()
    {
        return $this->id_post;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return post
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return post
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
     * Set posting_date
     *
     * @param \DateTime $postingDate
     * @return post
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
     * Set content
     *
     * @param string $content
     * @return post
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
     * Set blog
     *
     * @param \WebManagementBundle\Entity\blog $blog
     * @return post
     */
    public function setBlog(\WebManagementBundle\Entity\blog $blog = null)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return \WebManagementBundle\Entity\blog 
     */
    public function getBlog()
    {
        return $this->blog;
    }
    
    /**
     * Set select_blog
     *
     * @param select_blog
     * @return post
     */
    public function setSelectBlog($select_blog)
    {
        $this->select_blog = $select_blog;

        return $this;
    }

    /**
     * Get select_blog
     *
     * @return select_blog 
     */
    public function getSelectBlog()
    {
        return $this->select_blog;
    }
    
    //--------------------------------------CHOICE LIST---------------------------------------------------------------------------
    public function getChoiceList($entity, $order, $getID )
    {
		$common_query = $this->controller_obj->get('app.common_queries');	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:".$entity, $order );
    	$option_list = NULL;
    	
    	foreach($values_list as $key=>$value)
    	{
				$option_list[$value->$getID()] = $value->getTitle();
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
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:post", $column, $value, 'posting_date');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:post", 'posting_date');
		}
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdPost();				
				$show_list[$i]['Posting Date']= $table_list_query[$i]->getPostingDate()->format('M-d-Y');
				$show_list[$i]['Written By']= $table_list_query[$i]->getUsername();
				$show_list[$i]['Email']= $table_list_query[$i]->getEmail();
				$show_list[$i]['Blog']= $table_list_query[$i]->getBlog()->getTitle();
				$show_list[$i]['Content']= $table_list_query[$i]->getContent();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;			
			$show_list[0]['Posting Date']= NULL;
			$show_list[0]['Written By']= NULL;
			$show_list[0]['Email']= NULL;
			$show_list[0]['Blog']= NULL;
			$show_list[0]['Content']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//------------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {
    	$blog_option_list = $this->getChoiceList("blog", "title", "getIdBlog");
		$fields = array("username","email", "posting_date", "select_blog", "content");
		$fields_types = array("text", "email", "date", "choice", "texteditor");
		$options = ($add_options != NULL )? $add_options : array(NULL, NULL, array('format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y')), "data" => new \DateTime("now")), array("choices"=>$blog_option_list), NULL);
		$items['entity'] = "\post";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}	 
//---------------------------------------------GENERATE FORM FUNCTIONS--------------------------------------------------------
	
//---------------------------------------------PERSIST DATA-------------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new post();
		$new_instance->setUsername($data_obj->getUsername());
		$new_instance->setPostingDate($data_obj->getPostingDate());
		$new_instance->setEmail($data_obj->getEmail());					
		$new_instance->setContent($data_obj->getContent());					
		$em = $this->controller_obj->getDoctrine()->getManager();
		$blog_repo = $em->getRepository("WebManagementBundle:blog");
		$blog = $blog_repo->find($data_obj->getSelectBlog());
		$new_instance->setBlog($blog);
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
		$current_repo = $em->getRepository("WebManagementBundle:post");
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
		$blog_option_list = $this->getChoiceList("blog", "title", "getIdBlog");
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:post");
		$item_found = $entity_repo->find($item_id);
		$options = array(
						 array("data"=>$item_found->getUsername(), 'disabled'=>FALSE),						 
						 array("data"=>$item_found->getEmail(), 'disabled'=>FALSE),						 
						 array("data"=>$item_found->getPostingDate(), 'format' => 'MMMM dd yyyy', 'years'=>range((date('Y')-100), date('Y'))),
						 array("choices"=>$blog_option_list, "preferred_choices"=> array($item_found->getBlog()->getIdBlog()), 'disabled'=>FALSE),
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
		$item = $em->getRepository("WebManagementBundle:post")->find($item_id);
		$item->setUsername($data_obj->getUsername());			
		$item->setEmail($data_obj->getEmail());			
		$item->setPostingDate($data_obj->getPostingDate());
		$blog_repo = $em->getRepository("WebManagementBundle:blog");
		$blog = $blog_repo->find($data_obj->getSelectBlog());
		$item->setBlog($blog);
		$item->setContent($data_obj->getContent());			
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//---------------------------------------------------
//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:post", "id_post", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id Post: </strong>".$row[0]->getIdPost()."</div>
								<div>"."<strong> Username: </strong> ".$row[0]->getUsername()."</div>
								<div>"."<strong> Email: </strong> ".$row[0]->getEmail()."</div>
								<div>"."<strong> Posting Date: </strong> ".$row[0]->getPostingDate()->format('M-d-Y')."</div>
								<div>"."<strong> From Blog: </strong> ".$row[0]->getBlog()->getTitle()."</div>
								<div><strong> Content: </strong></div><div class = 'bg-content'>".$row[0]->getContent()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("username","email", "posting_date", "blog_id");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
