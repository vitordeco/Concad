<?php
namespace Application\View\Helper;
use Zend\View\Helper\AbstractHelper;

class Pagination extends AbstractHelper
{
	protected $pagination = array();
	
	public function __invoke($pagination, $limit=10)
	{
		$pagination = (array) $pagination;
		//echo'<pre>'; print_r($pagination); exit;
		
    	//set pagination navigation
    	$nav_start = $pagination['current'] - floor($limit/2);
    	$nav_start = $nav_start > ($pagination['last'] - $limit) ? ($pagination['last'] - $limit + 1) : $nav_start;
    	$nav_start = $nav_start < $pagination['first'] ? $pagination['first'] : $nav_start;
 		
    	$nav_end = $nav_start + $limit;
    	$nav_end = $nav_end > $pagination['last'] ? $pagination['last'] : $nav_end;
 		
    	for( $i=$nav_start; $i<=$nav_end; $i++ )
	   	{
	   		if( count($pagination['nav']) < $limit )
	   		{
	   			$pagination['nav'][] = $i;
	   		}
		}
		
		$this->pagination = $pagination;
		return $this->renderHtml();
	}
	
	public function renderHtml()
	{
		$html = '';
		
		if( is_array($this->pagination['nav']) )
		{
			$html .= '<ul class="paginator">';
			
			//first and prev
			if( $this->pagination['current'] == $this->pagination['first'] )
			{
				$html .= '<li class="first disabled"><a href="javascript:;"></a></li>';
				$html .= '<li class="prev disabled"><a href="javascript:;"></a></li>';
			} else {
				$html .= '<li class="first"><a href="' . $this->getLink(1) . '"></a></li>';
				$html .= '<li class="prev"><a href="' . $this->getLink($this->pagination['prev']) . '"></a></li>';
			}
			
			//navigation
			foreach( $this->pagination['nav'] as $page )
			{
				if( $this->pagination['current'] == $page )
				{
					$html .= '<li class="page active"><a href="javascript:;">' . $page . '</a></li>';
				} else {
					$html .= '<li class="page"><a href="' . $this->getLink($page) . '">' . $page . '</a></li>';
				}
			}
			
			//last and next
			if( $this->pagination['current'] == $this->pagination['last'] )
			{
				$html .= '<li class="next disabled"><a href="javascript:;"></a></li>';
				$html .= '<li class="last disabled"><a href="javascript:;"></a></li>';
			} else {
				$html .= '<li class="next"><a class="next link" href="' . $this->getLink($this->pagination['next']) . '"></a></li>';
				$html .= '<li class="last"><a class="next link" href="' . $this->getLink($this->pagination['last']) . '"></a></li>';
			}
			
			$html .= '</ul>';
		}
	
		return $html;
	}
	
	public function renderHtmlDiv()
	{
		$html = '';

		if( is_array($this->pagination['nav']) )
		{
			$html .= '<div class="paginator">';
			
			//first and prev
			if( $this->pagination['current'] == $this->pagination['first'] )
			{
				$html .= '<span class="prev disabled"><i class="fa fa-angle-double-left"></i></span>';
				$html .= '<span class="prev disabled"><i class="fa fa-angle-left"></i></span>';
			} else {
				$html .= '<a class="prev link" href="' . $this->getLink(1) . '"><i class="fa fa-angle-double-left"></i></a>';
				$html .= '<a class="prev link" href="' . $this->getLink($this->pagination['prev']) . '"><i class="fa fa-angle-left"></i></a>';
			}
			
			//navigation
			foreach( $this->pagination['nav'] as $page )
			{
				if( $this->pagination['current'] == $page )
				{
					$html .= '<span class="page current-page disabled">' . $page . '</span>';
				} else {
					$html .= '<a class="page link" href="' . $this->getLink($page) . '">' . $page . '</a>';
				}
			}
			
			//last and next
			if( $this->pagination['current'] == $this->pagination['last'] )
			{
				$html .= '<span class="next disabled"><i class="fa fa-angle-right"></i></span>';
				$html .= '<span class="next disabled"><i class="fa fa-angle-double-right"></i></span>';
			} else {
				$html .= '<a class="next link" href="' . $this->getLink($this->pagination['next']) . '"><i class="fa fa-angle-right"></i></a>';
				$html .= '<a class="next link" href="' . $this->getLink($this->pagination['last']) . '"><i class="fa fa-angle-double-right"></i></a>';
			}
			
			$html .= '</div>';
		}

		return $html;
	}
	
	public function getLink($page)
	{
		if( sizeof($_GET) > 0 )
		{
			$q = \Tropaframework\Security\Security::antiInjection($_GET);
			unset($q['page']);
			$q = '&' . http_build_query($q);
		}
		
		$link = strtok($_SERVER["REQUEST_URI"], '?') . '?page=' . $page . $q;
		return $link;
	}
}
?>