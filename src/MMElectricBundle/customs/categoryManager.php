<?php
namespace MMElectricBundle\customs;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class categoryManager{
	
	public $controller_obj;
	
	public $mm_electric_queries;
	
	public $common_query;
	
	public function Init($controller_obj){
		$this->controller_obj = $controller_obj;
		$this->mm_electric_queries = $this->controller_obj->get("app.mm_electric_queries");
		$this->mm_electric_queries->Init($this->controller_obj);
		$this->common_query = $this->controller_obj->get("app.common_queries");
	}
	
	
	
	public function getAllCategories(){
		$categories_query = $this->common_query->getColumnValuesFromTable($this->controller_obj, "WebManagementBundle:category", "name");
		$categories_arr = array();
		foreach( $categories_query as $key => $value){
			$categories_arr[$key] = $value['name'];
		}
		return$categories_arr;
	}
	public function getContentXCategory(){
		$content_arr = array();
		$content_arr["home"] = $this->getArticlesBy("Home");
		$content_arr["services"] = $this->getArticlesBy("Services");
		$content_arr["projects"] = $this->getArticlesBy("Project");	
		$content_arr["about"] = $this->getArticlesBy("About");
		$content_arr["certifications"] = $this->getArticlesBy("Certifications");
		$content_arr["contact"] = $this->getCompanyContacts(); 
		return $content_arr;
		
	}
	public function getArticlesBy($category){
		$articles_arr = array();
		$articles_query = $this->mm_electric_queries->getActiveTableValuesByCategory("WebManagementBundle:article", $category, "posting_date");
		foreach($articles_query as $key => $value){
			$articles_arr[$key]["id"] = $value->getIdArticle();
			$articles_arr[$key]["title"] = $value->getTitle();
			$articles_arr[$key]["url"] = $value->getUrlStorage();
			$articles_arr[$key]["album"] = $value->getAlbum() ? TRUE : FALSE;
			$articles_arr[$key]["date"] = $value->getPostingDate()->format('M-d-Y');
			$articles_arr[$key]["content"] = $value->getContent();
		}
			
//		dump($articles_arr);die;
		return $articles_arr ? $articles_arr : "No content found for this secction...";
	}
	public function getCompanyContacts(){
		$company_contact_arr = array();
		$company_contact_query = $this->mm_electric_queries->getCompanyAddressLocations();
		foreach($company_contact_query as $key => $value){
			$company_contact_arr[$key]["latitude"] = $value->getLatitude();
			$company_contact_arr[$key]["longitude"] = $value->getLongitude();
			$company_contact_arr[$key]["description"] = $value->getDescription();
			$company_contact_arr[$key]["street"] = $value->getAddress()->getStreet();
			$company_contact_arr[$key]["city"] = $value->getAddress()->getCity();
			$company_contact_arr[$key]["state"] = $value->getAddress()->getState();
			$company_contact_arr[$key]["country"] = $value->getAddress()->getCountry();
			$company_contact_arr[$key]["zipcode"] = $value->getAddress()->getZipCode();
			$company_contact_arr[$key]["email"] = $value->getAddress()->getEmail();
			$company_contact_arr[$key]["phone1"] = $value->getAddress()->getWorkPhone();
			$company_contact_arr[$key]["phone2"] = $value->getAddress()->getCellphone();
		}		
		return $company_contact_arr ? $company_contact_arr : "No content found for this secction...";
	}
	public function getPhotosAlbum($id_value){
		$photos_album_arr = array();
		$album_id = $this->mm_electric_queries->getColumnFromTableWhereId("id_article", $id_value, "WebManagementBundle:article", "");
		$photos_album_query = $this->mm_electric_queries->getJoinColumnFromTableWhereId("WebManagementBundle:photo", "album", "id_album", $album_id[0]->getAlbum()->getIdAlbum());
		foreach($photos_album_query as $key => $value){
			$photos_album_arr[$key]["id"] = $value->getIdPhoto();
			$photos_album_arr[$key]["url"] = "/mmelectric/web/".$value->getUrlStorage();
			$photos_album_arr[$key]["name"] = $value->getName();
			$photos_album_arr[$key]["description"] = $value->getDescription();
		}
		return $photos_album_arr ? $photos_album_arr : "No content found for this secction...";
	}
	public function getContractors(){
		$contractors_arr = array();
		$contractors_query = $this->mm_electric_queries->getActiveTableValuesByCategory("WebManagementBundle:links", "Contractors");
		foreach($contractors_query as $key => $value){
			$contractors_arr[$key]['title'] = $value->getTitle();
			$contractors_arr[$key]['url'] = $value->getUrlLinks();
		}
		return $contractors_arr ? $contractors_arr : "No content found for this secction...";
	}	
}
?>