<?php
if (!isset($_SESSION))
	session_start();
include ("load.php");

	class Routes extends UserClass {

		public $QURY;
		public $resh;
		public $reqh;
		public $pasm;
		public $uri;
		/*
		*
		* public function __construct
		* @parameters none
		*
		*/
		function __construct() {
			$this->pasm = new \PASM();
			$GET = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
			$this->QURY = null;
			if ($GET != null) {
				if ($GET == 'GET')
					$this->QURY = $_GET;
				else if ($GET == 'POST')
					$this->QURY = $_POST;
				else {
					var_dump($this->req_headers);//http_parse_query();
				}
				if (!isset($this->QURY['port']) || $this->QURY['port'] == null)
					$this->QURY['port'] = 80;
				if (!isset($this->QURY['user']) || $this->QURY['user'] == null)
					$this->QURY['user'] = "guest";
			}
			if (isset($_SESSION['token']))
				$this->QURY['token'] = $_SESSION['token'];
			if (isset($_SESSION['user']))
				$this->QURY['user'] = $_SESSION['user'];
			if (file_exists("routing.ini")) {
				$routes = unserialize(file_get_contents("routing.ini")); 
				$this->pasm->addr($routes)->movr()->end();
				$this->pasm->stack = array_unique($this->pasm->stack);
			}
			$this->reqHeaders();
			$this->resHeaders();
		}

		public function findRoute()
		{
			$this->load("routing.ini");
			
			foreach ($this->pasm->stack as $key) {
				if (basename($_SERVER['SCRIPT_NAME']) == $key['redirect'][0])
				{
					//echo '.';
					if (($this->QURY['token']) == ($key['token']))
					{
						if ((isset($this->QURY['group']) && isset($key['group']) && ($this->QURY['group']) == ($key['group'])) &&
							(isset($this->QURY['user']) && isset($key['user']) && ($this->QURY['user']) == ($key['user'])))
						{
							if (1 == ($key['allowed']))
							{
								echo ".";
								 //foreach($key as $keya => $value)
									$this->QURY = $key;
								return $key;
								//$this->route();
							}
							//else 
							//echo '.';
						}
						else if ((isset($this->QURY['user']) && isset($key['user']) && ($this->QURY['user']) == ($key['user'])))
						{
							if (1 == ($key['allowed']))
							{
								echo ".";
								 //foreach($key as $keya => $value)
									$this->QURY = $key;
								return $key;
								//$this->route();
							}
							//else 
							//echo '.';
						}
					}
				}
			}
			return -1;
		}
		
		/*
		*
		* public function addContract
		* @parameters recv, token, target, port, user
		*
		*/
		public function addContract() {
			if ($this->group_id > 0)
				return false;
			if (count($this->pasm->stack) == 0 && file_exists("routing.ini"))
				$this->load("routing.ini");
			
			$sp = $this->getContract();
			
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 1;
				$this->pasm->stack[$p] = $sp;
			}
			else {

				$this->pasm->addr([
					"recv" => $this->QURY['recv'], 
					"token" => $this->QURY['token'],
					"allowed" => 1,
					"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['target']], 
					"port" => $this->QURY['port']
					])
					->movr()
					->end();
				if (isset($this->QURY['req']))
					$this->pasm->save_stack_file();
			}
			
			return $this;
		}
		
		/*
		*
		* public function addUserToContract
		* @parameters recv, token, target, port, user
		*
		*/
		public function addUserToContract() {
			if ($this->group_id > 0)
				return false;
			if (count($this->pasm->stack) == 0 && file_exists("routing.ini"))
				$this->load("routing.ini");
			
			$sp = $this->getContract();
			
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 1;
				$this->pasm->stack[$p] = $sp;
			}
			else {

				$this->pasm->addr([
					"recv" => $this->QURY['recv'], 
					"token" => $this->QURY['token'],
					"allowed" => 1,
					"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']], 
					"port" => $this->QURY['port'],
					"user" => $this->QURY['user']
					])
					->movr()
					->end();
				if (isset($this->QURY['req']))
					$this->pasm->save_stack_file();
			}
			
			return $this;
		}
		
		/*
		*
		* public function addUserToContract
		* @parameters recv, token, target, port, user
		*
		*/
		public function addGroupToContract() {
			if ($this->group_id > 0)
				return false;
			if (count($this->pasm->stack) == 0 && file_exists("routing.ini"))
				$this->load("routing.ini");
			
			$sp = $this->getContract();
			
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 1;
				$this->pasm->stack[$p] = $sp;
			}
			else {

				$this->pasm->addr([
					"recv" => $this->QURY['recv'], 
					"token" => $this->QURY['token'],
					"group" => $this->QURY['group'],
					"allowed" => 1,
					"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['group'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']], 
					"port" => $this->QURY['port'],
					"user" => $this->QURY['user']
					])
					->movr()
					->end();
				if (isset($this->QURY['req']))
					$this->pasm->save_stack_file();
			}
			
			return $this;
		}
		
		/*
		*
		* public function remContract
		* @parameters none
		*
		*/
		public function remContract() {
			if ($this->group_id > 0)
				return false;
			
			$sp = $this->getContract();
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 0;
				$this->pasm->stack[$p] = $sp;
			}
			else {
				$this->pasm->addr([
					"recv" => $this->QURY['recv'],
					"token" => $this->QURY['token'],
					"allowed" => 0,
					"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']],
					"port" => $this->QURY['port']
					])
					->movr()
					->end();
			}
			return $this;
		}
		
		/*
		*
		* public function remContract
		* @parameters none
		*
		*/
		public function remUserFromContract() {
			if ($this->group_id > 0)
				return false;
			
			$sp = $this->getContract();
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 0;
				$this->pasm->stack[$p] = $sp;
			}
			else {
				$this->pasm->addr([
					"recv" => $this->QURY['recv'],
					"token" => $this->QURY['token'], 
					"allowed" => 0,
					"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']], 
					"port" => $this->QURY['port'], 
					"user" => $this->QURY['user']
					])
					->movr()
					->end();
			}
			return $this;
		}
		
		/*
		*
		* public function remContract
		* @parameters none
		*
		*/
		public function remGroupFromContract() {
			if ($this->group_id > 0)
				return false;
			
			$sp = $this->getContract();
			if ($sp != -1) {
				$p = array_search($sp, $this->pasm->stack);
				$sp['allowed'] = 0;
				$this->pasm->stack[$p] = $sp;
			}
			else {
				$this->pasm->addr([
					"recv" => $this->QURY['recv'],
					"token" => $this->QURY['token'], 
					"allowed" => 0,
					"redirect" => [($this->reqh['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['target']], 
					"port" => $this->QURY['port'], 
					"user" => $this->QURY['user']
					])
					->movr()
					->end();
			}
			return $this;
		}
		
		/*
		*
		* public function getContract
		* @parameters none
		*
		*/
		public function getContract() {
			$user = [
				"recv" => $this->QURY['recv'], 
				"token" => $this->QURY['token'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']], 
				"port" => $this->QURY['port']
			];
			$group = [
				"recv" => $this->QURY['recv'], 
				"token" => $this->QURY['token'],
				"group" => $this->QURY['group'],
				"allowed" => 1,
				"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['group'] . '/' . $this->QURY['user'] . '/' . $this->QURY['sub'] . '/' . $this->QURY['target']], 
				"port" => $this->QURY['port'],
				"user" => $this->QURY['user']
			];
			$redirect = [
				"recv" => $this->QURY['recv'], 
				"token" => $this->QURY['token'], 
				"allowed" => 1, 
				"redirect" => [basename($_SERVER['SCRIPT_NAME']), $this->QURY['base_dir'] . '/' . $this->QURY['target']], 
				"port" => $this->QURY['port'], 
				"user" => $this->QURY['user']
				
			];
			foreach ($this->pasm->stack as $key) {
				if (($redirect) == ($key) || ($user) == ($key) || ($group) == ($key))
				{
					if ($group == $key)
						return $group;
					return $redirect == $key ? $redirect : $user;
				}
				$redirect['allowed'] = 0;
				$user['allowed'] = 0;
				$group['allowed'] = 0;
				if (($redirect) == ($key) || ($user) == ($key) || ($group) == ($key))
				{
					if ($group == $key)
						return $group;
					return $redirect == $key ? $redirect : $user;
				}
				$redirect['allowed'] = 1;
				$user['allowed'] = 1;
				$group['allowed'] = 1;
			}
			$this->pasm->save_stack_file();
			return -1;
		}
		
		/*
		*
		* public function getContract
		* @parameters string
		*
		*/
		public function http_parse_query(string $query) {
			$parameters = array();
			$query = explode('?', $query);
			$queryParts = explode('&', $query[1]);
			foreach ($queryParts as $queryPart) {
				$keyValue = explode('=', $queryPart, 2);
				if (substr($keyValue,strlen($keyValue)-3) != "[]")
					$parameters[$keyValue[0]] = $keyValue[1];
				else {
					$keyValue = substr($keyValue,strlen($keyValue)-3);
					$parameters[$keyValue][] = $keyValue[1];
				}
			}
			return $parameters;
		}
		
		public function route() {

			if (($sp0 = $this->getContract()) != -1 || ($sp1 = $this->findRoute()) != -1)
			{
				$sp = null;
				if ($sp0 == -1)
					$sp = $sp1;
				else {
					$sp = $sp0;
				}
				$script = $_SERVER['SCRIPT_NAME'];
				if ($sp['allowed'] == 0)
					header("Location: {$script}");
				$field = []; 
				$port = $sp['port'];
				$protocol = getservbyport($sp['port'],'tcp');
				$aim = $sp['redirect'][1];
				$domain = $sp['recv'];
				
				# Create a connection
				$url = "http://{$domain}:{$port}/{$aim}";
				if (1) {
					$handle = curl_init($url); 
					$this->reqHeaders();  
					$user_agent=$_SERVER['HTTP_USER_AGENT'];
					curl_setopt($handle, CURLOPT_HTTPHEADER, $this->reqh);
					//curl_setopt($handle, CURLOPT_HEADER, true);
					//curl_setopt($handle, CURLOPT_URL, $url);
					//curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($handle, CURLOPT_POST, true);
					//curl_setopt($handle, CURLOPT_FOLLOWLOCATION,true);
					curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($this->QURY));
					$page_contents = curl_exec($handle);
					echo $page_contents;
					
				}
				else {
					$data = "";
					foreach ($this->QURY as $key => $value) {
						$data .= "&{$key}={$value}";
					}
					$data = substr($data,1);
					header("Location: http://{$domain}:{$port}/{$url}?{$data}");
				}
			}
		}
	
		/*
		*
		* public function reqHeaders
		* @parameters none
		*
		*/
		public function reqHeaders() {
			$this->reqh = apache_request_headers();
			return $this;
		}

		/*
		*
		* public function resHeaders
		* @parameters none
		*
		*/
		public function resHeaders() {
			$this->resh = apache_response_headers();
			return $this;
		}

		/*
		*
		* public function save
		* @parameters string
		*
		*/
		public function save(string $filename = "") {
			$this->pasm->load_str($filename)
				->save_stack_file()
				->end();
			return $this;
		}
		
		/*
		*
		* public function load
		* @parameters string
		*
		*/
		public function load(string $filename= "") {
			$this->pasm->stack = [];
			$filename = "routing.ini";
			$this->pasm->recvr_stack($filename)
				->end();
			return $this;
		}

	}
?>
