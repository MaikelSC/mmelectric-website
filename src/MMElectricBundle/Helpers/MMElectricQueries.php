<?php
namespace MMElectricBundle\Helpers;

class MMElectricQueries {
	
	public $controller_obj;
	
	public function Init($controller_obj){
		$this->controller_obj = $controller_obj;
	}
	
	public function getActiveTableValuesByCategory($table, $category, $orderByField = ""){
		
		$orderedBy = $orderByField ? ("ORDER BY rows.".$orderByField." DESC") : "";
		$query = $this->controller_obj
										->getDoctrine()
										->getManager()
										->createQuery( 
														"SELECT rows, cat 
														FROM ".$table." rows 
														JOIN rows.category cat 
														WHERE cat.name = '".$category."' AND rows.active = '1'
														".$orderedBy
													)
										->getResult();
					
		return $query;
	}
	
	public function getCompanyAddressLocations(){
		
		$query = $this->controller_obj
										->getDoctrine()
										->getManager()
										->createQuery( 
														"SELECT loc, add 
														FROM WebManagementBundle:location loc 
														JOIN loc.address add"
													)
										->getResult();
					
		return $query;
	}
	
	public function getColumnFromTableWhereId($id, $id_value, $table, $column){
		$query = $this->controller_obj
										->getDoctrine()
										->getManager()
										->createQuery( 
														"SELECT item
														FROM ".$table." item 
														WHERE item.".$id." = ".$id_value.""
													)
										->getResult();
					
		return $query;
	}
	
	public function getJoinColumnFromTableWhereId($table, $assoc_table, $assoc_column, $assoc_value){
		
		$query = $this->controller_obj
										->getDoctrine()
										->getManager()
										->createQuery( 
														"SELECT items, items_assoc 
														FROM ".$table." items 
														JOIN items.".$assoc_table." items_assoc 
														WHERE items_assoc.".$assoc_column." = ".$assoc_value.""
													)
										->getResult();
					
		return $query;
	}
}
?>