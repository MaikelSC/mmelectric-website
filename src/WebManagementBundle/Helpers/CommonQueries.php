<?php
namespace WebManagementBundle\Helpers;

use Doctrine\ORM\EntityRepository;
use WebManagementBundle\Entity\backEndRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommonQueries
{	
	public function getAllOrderedByFieldFromTable($controller, $table, $orderByField = "")
	{
		$orderedBy = $orderByField ? ("ORDER BY item.".$orderByField." ASC") : "";
		$query = $controller->getDoctrine()->getManager()->createQuery( 'SELECT item FROM '.$table.' item '.$orderedBy)->getResult();
					
		return $query;
	}
	public function getColumnValuesFromTable($controller, $table, $column)
	{
		$conn = $controller->getDoctrine()->getManager()->getConnection();
		$query = $conn->prepare('SELECT '.$column.' FROM '.$table);
		$query->execute();
		return $query->fetchAll();
		/*$query = $controller->getDoctrine()->getManager()->createQuery( 'SELECT item.'.$column.' FROM '.$table.' item')->getResult();
					
		return $query;*/
	}
	public function getRowFromTableWhereColumnValue($controller, $table, $column, $value, $orderByField = "")
	{
		$orderedBy = $orderByField ? ("ORDER BY item.".$orderByField." ASC") : "";
		$query = $controller->getDoctrine()->getManager()->createQuery( "SELECT item FROM ".$table." item WHERE item.".$column." = '".$value."' ".$orderedBy)->getResult();
					
		return $query;
	}
	public function getAllDBDependencies( $controller, $table = NULL )
	{
		$conn = $controller->getDoctrine()->getManager()->getConnection();
		$sm = $conn->getSchemaManager();
		$tables = array();
		if($table != NULL){
			$tables[] =  $sm->listTableDetails($table);
		}
		else{
			$tables = $sm->listTables();
		}		
		$dependencies_arr = array(); //array of dependencies per classes ($dependencies_arr[class-name] = array-dependencies)
		$mn_relations = array(); //array of M-N relationships
		foreach($tables as $key => $table_obj){
			if(!strpos($table_obj->getName(), "_relation")) //check if the table isn't a M-N relationship linker table
			{ 
				$list_table_fk = $sm->listTableForeignKeys($table_obj->getName());
				$count = count($list_table_fk);			
				$foreign_tables_arr = array();
				for($i = 0; $i < $count; $i++){
						$foreign_tables_arr[$i] = $list_table_fk[$i]->getForeignTableName();	//get all dependent tables		
				}
				$dependencies_arr[$table_obj->getName()] = $foreign_tables_arr; // filling the array of tables dependencies
			}
			else
			{ 
				$mn_relations[] = $sm->listTableForeignKeys($table_obj->getName()); //getting the M-N relationship linker tables
			}			
		}
		foreach($mn_relations as $key => $value){
			$dependencies_arr[$value[0]->getForeignTableName()][] = $value[1]->getForeignTableName(); // adding the linker tables dependencies to the M-N relationship tables
			$dependencies_arr[$value[1]->getForeignTableName()][] = $value[0]->getForeignTableName();			
		}
		return $dependencies_arr;
	}
}
?>