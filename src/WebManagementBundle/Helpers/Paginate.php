<?php
namespace WebManagementBundle\Helpers;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class Paginate 
{
	private $items_x_page;
	private $next_page;
	private $total_pages;
	private $items_array;
   function __construct($items_array, $items_x_page, $next_page)
   {
   		$this->items_array = $items_array;
   		$this->items_x_page = (int)$items_x_page;   		
   		if($this->totalPages() >= $next_page)
   		{
			$this->next_page = $next_page;
		}
   		else
   		{
			$this->next_page = 1;
		}   		
   }
   public function totalPages()
   {
   	$remainder = gmp_div_r(count($this->items_array), $this->items_x_page);
   	$quotient = gmp_intval(gmp_div_q(count($this->items_array), $this->items_x_page));
   	if( $quotient > 0 )
   	{
   		if( $remainder > 0 )
   		{
			$this->total_pages = $quotient + 1;
		}
		else
		{
			$this->total_pages = $quotient;
		}	
	}
	else
	{
		$this->total_pages = 1;
	}
	return $this->total_pages;
   }
   public function setPagination()
   {
   	$last = $this->next_page * $this->items_x_page;
   	$from_item = $last - $this->items_x_page;
   	$to_item = (count($this->items_array) < $last)? count($this->items_array) : $last;
   	$array_to_show = array_slice($this->items_array, $from_item, $this->items_x_page);
   	$pagination_obj = array();
   	$pagination_obj['items_list'] = $array_to_show;
   	$pagination_obj['from_item'] = $from_item + 1;
   	$pagination_obj['to_item'] = $to_item;
   	$pagination_obj['total_items'] = count($this->items_array);
   	$pagination_obj['next_page'] =  $this->next_page;
   	$pagination_obj['total_pages'] = $this->totalPages();
   	$pagination_obj['items_x_page'] = $this->items_x_page;
   	return $pagination_obj;
   }
}

?>