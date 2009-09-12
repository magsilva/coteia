<?php
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 
Copyright (C) 2007 Marcio Ghiraldelli <marcio.gh@gmail.com>
Modified by Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

require_once('Config.class.php');
require_once('FileUtil.class.php');
require_once('smarty/Smarty.class.php');

class RequestProcessor
{
	var $template_engine;
	
	public function __constrution()
	{
		
	}
	
	function setTemplateEngine($template_engine)
	{
		$this->template_engine = $template_engine;
	}

/**
	 * Get the URL of the current script
	 */
	function getServerRootURL()
	{
	    $host = $_SERVER['HTTP_HOST'];
	    $port = $_SERVER['SERVER_PORT'];
	    $s = isset($_SERVER['HTTPS']) ? 's' : '';
	    if (($s && $port == '443') || (!$s && $port == '80')) {
	        $p = '';
	    } else {
	        $p = ':' . $port;
	    }
	    
	    return "http$s://$host$p";
	}

	
	/**
	 * Get the URL of the current script
	 */
	function getServerURL()
	{
		$path = dirname($_SERVER['SCRIPT_NAME']);
	    if ($path[strlen($path) - 1] != '/') {
	        $path .= '/';
	    }
	
	    return $this->getServerRootURL() . $path;
	}
	
	/*
	function redirect($url = null, $action = null, $next_action = null, $return_to = null)
	{
		// If we didn't assigned an URL, the $url actually has the action.		
		if ($url != null && (strpos($url, 'http') === FALSE || strpos($url, 'http') != 0)) {
			// And, if our $url is the action, then $action is the $next_action.
			if ($action != null) {
				$next_actions = $action;
			}
			$action = $url;
			$url = null;
		}

		if ($url == null) {
			$url = $this->getServerURL();
		}

		
	    if ($action != null) {
	        if (strpos($url, '?') === false) {
	            $url .= '?action=' . $action;
	        } else {
	            $url .= '&action=' . $action;
	        }
	    }
	    
	    if ($next_action != null) {
	    	$url .= '&next_action=' . $next_action;
	    }

		if ($return_to != null) {
			$url .= '&return_to=' . htmlentities($return_to);
		}

	    if ($action != null && isset($_GET['lang'])) {
	        if (strpos($url, '?') === false) {
	            $url .= '?lang=' . $this->template_engine->language;
	        } else {
	            $url .= '&lang=' . $this->template_engine->language;
	        }
	    }

		$this->log->info("Redirecting to action '$action', next action is '$next_action', and return URL is '$return_to' ($url)");
	    header('Location: ' . $url);
	    exit(0);
	}
	*/
	
	public function processRequest($request, $response, $path, $mappings)
	{
		$requestMapping = null;
		
		foreach ($mappings as $mapping) {
			if ($mapping->matches($path)) {
				$requestMapping = $mapping;
				break;
			}
		}
		
		if (is_null($requestMapping)) {
			throw new Exception('No matching action for path ' . $path);
		}
		
		$action = $requestMapping->getAction();
		$forward = $action->execute($request, $response);
		if (! $forward) {
			throw new Exception('No forward set for the given action');
		}
		self::processForward($forward, $response);
	}
	
	/*
	function showMessages()
	{
		// If any messages are pending, get them and display them.
		$messages = $this->server->getMessages();
		foreach ($messages as $m) {
    		$this->template_engine->addMessage($m);
		}
		$this->server->clearMessages();
	}
	*/
	
	/*
	function forward($method, $request, $action)
	{
		$this->log->info("Forwarding to action '$action'");
		// Dispatch request to appropriate handler.
		$handler = $this->getHandler($action);
		if ($handler !== null) {
			$this->log->info("Found a handler for action '$action'");
			list($filename, $clsname) = $handler;
			require_once($filename);
			$action = new $clsname($this);
			$this->log->info("Handing over the job to the handler '$clsname'");
			$action->process($method, $request);
		} else {
			$this->log->info("No suitable handler found for action '$action', redirecting to the main page.");
			$this->template_engine->display('main.tpl');
		}
		exit();
	}
	*/
	
	function processForward($forward, &$response = null)
	{
		if ($forward[0] == ".") {
			$forward = substr($forward, 1);
			$url_chain = "?do=". $forward;
			header("Location: ". $url_chain);
		} else {
			$fileExtension = FileUtil::getExtension($forward);
			switch ($fileExtension) {
				case 'tpl':
					$config = Config::instance();
					$templateFile = $config->smartyTemplateDir . '/' . basename($forward);
					if (! FileUtil::isFile($templateFile)) {
						throw new Exception('The action forwarded the application to an invalid view');
					}

					$smarty = new Smarty();
					$smarty->template_dir = $config->smartyTemplateDir;
					$smarty->compile_dir = $config->smartyCompileDir;
					$smarty->config_dir = $config->smartyConfigDir;
					$smarty->cache_dir = $config->smartyCacheDir;
					$smarty->caching = 1;
					$smarty->cache_lifetime = 60;
					// $smarty->compile_check = true;
					foreach ($response as $key => $value) {
						$smarty->assign($key, $value);
					}
					$smartyCacheId = sha1($response['title']);
					
					// header('Content-Type: text/html; charset=utf-8');
					$smarty->display($templateFile, $smartyCacheId);	
					break;
					
				case 'php':
					if (! FileUtil::isFile($forward)) {
						throw new Exception('The action forwarded the application to an invalid view');
					}
					include($forward);
					break;
					
				default:
					include($forward);
			}
		}
	}

}

?>