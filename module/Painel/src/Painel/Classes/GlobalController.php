<?php
namespace Painel\Classes;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Application\Validate\ValidateAbstract;

/**
 * @author Vitor Deco
 * Esta classe possui funcoes que encurtam as funcoes padroes do ZF2
 */
class GlobalController extends AbstractActionController
{
	/**
	 * set vars to view in controller
	 * @var array
	 */
	protected $view = array();
	
	/**
	 * @var \Tropaframework\Head\Head
	 */
	protected $head = null;
	
	/**
	 * Tables
	 * @var object
	 */
	protected $tb = null;

	/**
	 * Database adapter
	 * @var \Zend\Db\Zend\Db\Adapter\AdapterInterface
	 */
	protected $adapter = null;
	
	/**
	 * @var Object
	*/
	public $post = null;
	
	/**
	 * @var Object
	*/
	public $get = null;
	
	protected function init()
	{
	    //call actions
	    $method = $this->params()->fromPost('method');
	    if( method_exists($this, $method) ) $this->$method();
	}
	
	protected function upload()
	{
	    try
	    {
	        //arquivo
	        $file = $_FILES['file'];
            
	        //upload
	        $upload = new \Tropaframework\Upload\Upload('/assets/uploads/');
	        $uploadResult = $upload->setExtensions(['jpg','png'])->file($file, "tmp");
	        if( $uploadResult === false ) throw new \Exception($upload->getError());
	        $imagem = $upload->getFilenameCurrent();
            $filename = '/assets/uploads/tmp/' . basename($imagem);

	        //resize
//	        $file = $_SERVER['DOCUMENT_ROOT'] . $filename;
//	        $image = new \Tropaframework\Upload\SimpleImage($file);
//	        $image->fitToWidth(600)->toFile($file, null, 100);
            
	        $return = array(
	            'filename' => $filename,
	        );
	        
	    } catch( \Exception $e ){
	        $return = array(
	            'error' => 1,
	            'message' => $e->getMessage(),
	        );
	    }
	
	    echo json_encode($return); exit;
	}
	
	/**
	 * Estende o metodo para definir os parametros globais da class
	 * @return void
	 */
	protected function attachDefaultListeners()
	{
		parent::attachDefaultListeners();
		
		//definir lista de tabelas do banco de dados
		$this->tb = $this->getServiceLocator()->get('tb');
		
		//definir banco de dados
		$this->adapter = $this->getServiceLocator()->get('db');
	}

	public function onDispatch(MvcEvent $e)
	{
		//=================================
		//controlar as permissões de acesso
	    //=================================
		$response = $this->checkPermissions();
		if( $response !== true ) return $response;
		
		//=================================
		//definir cabeçalho do HTML
		//=================================
		$version = time();
		$this->head = new \Tropaframework\Head\Head($this->getServiceLocator(), 'Application', $version, '500');
		$this->head->setTitle('MANAGER • ');
		$this->head->setCss('normalize.css');
		$this->head->addMask();
		$this->head->addCalendar();
		$this->head->setJs('helpers/forms.js');
		$this->head->setJs('helpers/formDataValues.js');
		$this->head->setJs('helpers/message.js');

		//set bootstrap
        $this->head->setCss('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', true);
        $this->head->setJs('/assets/application/js/extensions/jquery-1.11.0.min.js', 'text/javascript');
        $this->head->setJs('bootstrap/bootstrap.bundle.min.js');

		//=================================
		//definir $_POST como $this->post podendo tratar no site inteiro
		//=================================
		$this->post = $this->view['post'] = $this->params()->fromPost();
		
		//=================================
		//definir $_GET como $this->get podendo tratar no site inteiro
		//=================================
		$this->get = $this->view['get'] = $this->params()->fromQuery();
		
		//call parent method
		return parent::onDispatch($e);
	}
	
    public function setTitle($title)
	{
		$this->head->setTitle($title);
		$this->head->setMeta('title', $title);
		$this->setBreadCrumb( $title, $this->get['id'] );
		$this->layout()->setVariable('title', $title);
	}
	
	public function setDescription($description)
	{
	    $this->head->setDescription($description);
	}
	
	public function setBreadCrumb( $title, $id = null )
	{
	     
	    $controller = $this->layout()->routes['controller'];
	    $action = $this->layout()->routes['action'];
	
	    $pages = [];
	
	    if ( $action == 'index' ){
	
	        $pages[0]['title'] = $title;
	        $pages[0]['link'] = 'javascript:;';
	
	    } else {
	
	        $pages[0]['title'] = $title;
	        $pages[0]['link'] = '/painel/'.$controller.'/index';
	
	        if ( $id ){
	
	            $pages[1]['title'] = 'Editar';
	            $pages[1]['link'] = 'javascript:;';
	
	        } else {
	
	            $pages[1]['title'] = 'Adicionar';
	            $pages[1]['link'] = 'javascript:;';
	
	        }
	         
	    }
	     
	    $this->layout()->setVariable('breadcrumb', $pages);
	     
	}
	
	/**
	 * controlar as permissões de acesso
	 */
	private function checkPermissions()
	{
		//definir rotas
		$routes = $this->layout()->routes;
		
		//definir usuário
		$me = $this->layout()->me;
		
		//definir url atual
		$url = $_SERVER['REQUEST_URI'];
		
		try 
		{
		    //restringir acesso para os controllers
		    $cond1 = empty($me); //se não tiver logado
		    $cond2 = ($me->nivel != 2); //se nível não for 2
		    $cond3 = ($routes['module'] == 'painel'); //se module for Painel
		    $cond4 = !in_array($routes['controller'], ['login']); //se o controller não for "login"
		    if( ($cond1 || $cond2) && $cond3 && $cond4 )
		    {
				throw new \Exception(ValidateAbstract::ERROR_PERMISSAO_ACESSO);
			}
			
			return true;
			
		} catch( \Exception $e ){
		    
		    if( ($routes['controller'] != 'index') )
		    {
	           $this->flashMessenger()->addErrorMessage($e->getMessage());
		    }
		    
	        return $this->redirect()->toUrl('/painel/login?r=' . $url); exit;
	        
		}
	}
	
	private function headerAccessControl()
	{
	    header('Access-Control-Allow-Origin: *');
	    header("Access-Control-Allow-Credentials: true");
	    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	    header('Access-Control-Max-Age: 1000');
	    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
	}
}