<?php
namespace WebManagementBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use WebManagementBundle\Helpers\CommonQueries;
use WebManagementBundle\Controller\ShortcutController;
use WebManagementBundle\Form\GenericForm;

/**
* @ORM\Entity(repositoryClass = "WebManagementBundle\Entity\userRepository")
* @ORM\Table(name="tb_user")
* @UniqueEntity(fields={"username"}, message="Sorry, this username already exist.")
* @UniqueEntity(fields={"email"}, message="Sorry, this email already exist.")
*/
class user implements UserInterface, \Serializable 
{	
//-----------------------------------------ATTRIBUTES-------------------------------------------------------	
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id_user;
	
	/**
	* @ORM\Column(type="string", length = 15)
	* @Assert\NotBlank()
	*/
	private $username;
	
	/**
	* 
	* @ORM\Column(type="string", length=64) 
	* @Assert\NotBlank()
	*/
	private $password;
	/**
	* @ORM\ManyToMany(targetEntity="user_roles")
	* @ORM\JoinTable(name="tb_user_roles_relation",
	* 				joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id_user")},
	* 				inverseJoinColumns={@ORM\JoinColumn(name="roles_id", referencedColumnName="id_roles")}
	* )
	*/
	private $roles;	
	/**
	* @ORM\Column(type = "string", length = 60)
	*/
	private $email;	
	/**
	* @ORM\Column(type = "boolean")
	*/
	private $is_active;
	private $controller_obj;
	private $user_roles;
//----------------------------------FUNCTIONS--------------------------------------------------------------
	function __construct($controller_obj = null, $is_active = true) 
	{
		$this->is_active = $is_active;
		$this->roles = new ArrayCollection();
		$this->controller_obj = $controller_obj;
	}
	
    /**
     * Get id_user
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->id_user;
    }
    
    /**
     * Set username
     *
     * @param string $username
     * @return user
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
     * Get Roles
     *
     * @return string 
     */
    public function getRoles()
    {
    	$array_roles = array();
    	for($i = 0; $i < count($this->roles); $i++){
			$array_roles[$i]=($this->roles[$i]->getRole());
		}
         return $array_roles;
//         return $this->roles;
    }    
    public function setRoles($roles)
    {
		$this->roles[0] = $roles;
	}
    public function getRolesAsString()
    {
    	$roles_as_string = '';
		for($i = 0; $i < count($this->roles); $i++){
			if($i === 0)
				$roles_as_string .= $this->roles[$i]->getRole();
			else
				$roles_as_string .= ", ".$this->roles[$i]->getRole();
		}
		return $roles_as_string;
	}
	public function getUserRoles()
	{
		return $this->user_roles;
	}
	public function setUserRoles($rol)
	{
		$this->user_roles = $rol;
	}
    /**
     * Set user_password
     *
     * @param string $userPassword
     * @return user
     */
    public function setPassword($userPassword)
    {
        $this->password = $userPassword;

        return $this;
    }
	/**
     * Set email
     *
     * @param string $email
     * @return user
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return user
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
    /**
     * Get user_password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
    public function getSalt()
    {
        return null;
    }
	public function eraseCredentials()
    {
    	
    }
     public function serialize()
    {
        return serialize(array(
            $this->id_user,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id_user,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    /**
     * Add roles
     *
     * @param \WebManagementBundle\Entity\roles $roles
     * @return user
     */
    public function addRole(\WebManagementBundle\Entity\user_roles $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \WebManagementBundle\Entity\roles $roles
     */
    public function removeRole(\WebManagementBundle\Entity\user_roles $roles)
    {
        $this->roles->removeElement($roles);
    }
    
//--------------------------------------CHOICE LIST------------------------------------------------------------------------
    public function getChoiceList()
    {
		$common_query = $this->controller_obj->get('app.common_queries');    	
    	$values_list = $common_query->getAllOrderedByFieldFromTable($this->controller_obj, "WebManagementBundle:user_roles", "id_roles" );
    	$option_list = NULL;
    	
    	foreach($values_list as $key=>$value)
    	{
    		$option_list[$value->getIdRoles()] = $value->getRole();			
		}
		return $option_list;
	}
//----------------------------------//----CHOICE LIST------//--------------------------------------------------------------
	
//---------------------------------------------GENERATE LIST VIEW----------------------------------------------------------
	public function showList($column, $value)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$table_list_query = array();
		if(($column != NULL) && ($value != NULL)){
			$table_list_query = $common_query->getRowFromTableWhereColumnValue($this->controller_obj,"WebManagementBundle:user", $column, $value, 'username');
		}
		else{
			$table_list_query = $common_query->getAllOrderedByFieldFromTable($this->controller_obj,"WebManagementBundle:user", 'username');
		}	
		$show_list = array();
		if(count($table_list_query)>0)
		{
			for($i = 0; $i < count($table_list_query); $i++)
			{
				$show_list[$i]['id']= $table_list_query[$i]->getIdUser();
				$show_list[$i]['User name']= $table_list_query[$i]->getUsername();
				$show_list[$i]['Email']= $table_list_query[$i]->getEmail();
				$show_list[$i]['Roles']= $table_list_query[$i]->getRolesAsString();
			}
		}
		else
		{
			$show_list[0]['id']= NULL;
			$show_list[0]['User name']= NULL;
			$show_list[0]['Email']= NULL;
			$show_list[0]['Roles']= NULL;
		}
		
		return $show_list;
	}
//------------------------------------------//---GENERATE LIST VIEW---//---------------------------------------------------
	
//---------------------------------------------GENERATE FORM FUNCTION------------------------------------------------------
    public function addElementForm($add_options = NULL)
    {  
    	$option_list = $this->getChoiceList();    	
		$fields = array("username","password", "email", "user_roles");
		$fields_types = array("text", "password","email", "choice");
		$options = ($add_options != NULL )? $add_options : array(NULL, NULL, NULL, array("choices"=>$option_list, 'preferred_choices'=>array(2)));
		$items['entity'] = "\user";
		$items['fields'] = $fields;
		$items['options'] = $options;
		$items['fields_types'] = $fields_types;
		return $items;
	}
//------------------------------------------//---GENERATE FORM FUNCTION----//----------------------------------------------
	
//---------------------------------------------PERSIST DATA----------------------------------------------------------------
	public function persistData($data_obj)
	{
		$new_instance = new user();
		$new_instance->setUsername($data_obj->getUsername());
		$new_instance->setEmail($data_obj->getEmail());		
		$plainPass = $data_obj->getPassword();
		$encoder = $this->controller_obj->get('security.password_encoder');
		$encodedPass = $encoder->encodePassword($new_instance, $plainPass);
		$new_instance->setPassword($encodedPass);
		$em = $this->controller_obj->getDoctrine()->getManager();
		$role = $em->getRepository('WebManagementBundle:user_roles')->find($data_obj->getUserRoles());
		$new_instance->setRoles($role);
		$em->persist($new_instance);
		$em->flush();
	}
//------------------------------------------//---PERSIST DATA---//---------------------------------------------------------
	
//---------------------------------------------REMOVE ITEM-----------------------------------------------------------------
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
		$current_repo = $em->getRepository("WebManagementBundle:user");
		$items_found = array();		
		for($i = 0; $i < count($items_ids_list); $i++){
			$items_found[$i] = $current_repo->find($items_ids_list[$i]);
			$em->remove($current_repo->find($items_ids_list[$i]));
		}
		if(count($items_found) > 0){
			$em->flush();
			return true;
		}
		else{
			return false;
		}		
	}
//------------------------------------------//---REMOVE ITEM---//----------------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM FORM----------------------------------------------------------
    public function updateItemForm($item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$entity_repo = $em->getRepository("WebManagementBundle:user");
		$item_found = $entity_repo->find($item_id);
		$option_list = $this->getChoiceList();
		$common_query = $this->controller_obj->get('app.common_queries');
		$user_role = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:user_roles", 'role' , $item_found->getRoles()[0]);
		$options = array(
						 array("data"=>$item_found->getUsername()),
						 NULL,
						 array("data"=>$item_found->getEmail()),
						 array("choices"=>$option_list, 'preferred_choices'=>array($user_role[0]->getIdRoles()))
						);
		$form_array['items'] = $this->addElementForm($options);
		$form_array['instance'] = $item_found;
		return $form_array;
	}
//------------------------------------------//-----UPDATE ITEM FORM----//--------------------------------------------------
    
//-----------------------------------------------UPDATE ITEM ACTION--------------------------------------------------------
    public function updateData($data_obj, $item_id)
    {
		$em = $this->controller_obj->getDoctrine()->getManager();
		$item = $em->getRepository("WebManagementBundle:user")->find($item_id);
		$item->setUsername($data_obj->getUsername());
		$item->setEmail($data_obj->getEmail());		
		$plainPass = $data_obj->getPassword();
		$encoder = $this->controller_obj->get('security.password_encoder');
		$encodedPass = $encoder->encodePassword($this, $plainPass);
		$item->setPassword($encodedPass);
		$role = $em->getRepository('WebManagementBundle:user_roles')->find($data_obj->getUserRoles());
		$item->setRoles($role);
		$em->flush();
	}
//-----------------------------------------//------UPDATE ITEM ACTION----//------------------------------------------------

//-------------------------------------------------VIEW ITEM------------------------------------------------------------------
	public function viewFinder($id)
	{
		$common_query = $this->controller_obj->get('app.common_queries');
		$row = $common_query->getRowFromTableWhereColumnValue($this->controller_obj, "WebManagementBundle:user", "id_user", $id);
		$row_array = array();
		$row_array["content"] = "<div>"."<strong> Id User: </strong>".$row[0]->getIdUser()."</div>
								<div>"."<strong> Username: </strong> ".$row[0]->getUsername()."</div>
								<div>"."<strong> Email: </strong> ".$row[0]->getEmail()."</div>
								<div>"."<strong> Roles: </strong> ".$row[0]->getRolesAsString()."</div>";
		
		return $row_array;
	}
//----------------------------------//-------------VIEW ITEM-----------//---------------------------------------------------

//-------------------------------------------------FILTER COLUMNS-----------------------------------------------------------

	public function getfilterColumns()
	{
		return array("username", "email");
	}

//---------------------------------//----------------FILTER COLUMNS-----//--------------------------------------------------
 
}
