<?php
namespace Application\Classes;
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

    protected function restrict()
    {
        if( empty($this->layout()->me->nivel) )
        {
            $this->flashMessenger()->addErrorMessage(ValidateAbstract::ERROR_PERMISSAO_ACESSO);
            $r = "/" . $this->layout()->routes['controller'] . "/" . $this->layout()->routes['action'];
            return $this->redirect()->toUrl("/login?r=$r");
        }
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
// 	    $this->headerAccessControl();

		//=================================
		//controlar as permissões de acesso
	    //=================================
		$response = $this->checkPermissions();
		if( $response !== true ) return $response;
		
		//=================================
		//definir cabeçalho do HTML
		//=================================
        $version = time();
		$this->head = new \Tropaframework\Head\Head($this->getServiceLocator(), 'Application', $version, '375');
		$this->head->setTitle('CONCAD • ');
		$this->head->setCss('normalize.css');

		$this->head->addMask();
		$this->head->setJs('helpers/forms.js');
		$this->head->setJs('helpers/formDataValues.js');
		$this->head->setJs('helpers/message.js');
		
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
		$this->layout()->setVariable('title', $title);
	}
	
	public function setDescription($description)
	{
	    $this->head->setDescription($description);
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
		    $cond2 = in_array($routes['controller'], ['conta']); //se o controller for "conta"
		    if( $cond1 && $cond2 )
		    {
				throw new \Exception(ValidateAbstract::ERROR_PERMISSAO_ACESSO);
			}
			
			return true;
			
		} catch( \Exception $e ){
		    
	        $this->flashMessenger()->addErrorMessage($e->getMessage());
	        return $this->redirect()->toUrl('/login?r=' . $url); exit;
	        
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