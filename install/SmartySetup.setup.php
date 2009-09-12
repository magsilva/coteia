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
 
Copyright (C) 2007 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/
 
require_once(dirname(__FILE__) . '/../libs/Util.class.php');
require_once(dirname(__FILE__) . '/ModuleSetup.class.php');

/**
 * Smarty setup.
 *
 * Smarty uses four directories setup to work. Those are used for templates,
 * compiled templates, cached templates and config files. You may or
 * may not use caching or config files, but it is a good idea to set them up
 * anyways. It is also recommended to place them outside of the web server
 * document root. The web server PHP user will need write access to the cache and
 * compile directories as well.
 * 
 * mkdir  smarty
$> mkdir smarty/templates
$> mkdir smarty/templates_c
$> mkdir smarty/cache
$> mkdir smarty/configs
$> chown nobody:nobody smarty/templates_c
$> chown nobody:nobody smarty/cache
$> chmod 775 smarty/templates_c
$> chmod 775 smarty/cache
*/
// Smarty will need write access (windows users please ignore) to the  $compile_dir and  $cache_dir, 
class SmartySetup implements ModuleSetupJob
{
	private $templateDir;
	
	private $compiledTemplateDir;
	
	private $cacheDir;
	
	private $configDir;
	
	public function __construct($templateDir = null, $compiledTemplateDir = null, $cacheDir = null, $configDir = null)
	{
		if ($templateDir == null) {
			$templateDir = dirname(__FILE__) . '/../data/templates';
		}
		$this->templateDir = realpath($templateDir);
		
		if ($compiledTemplateDir == null) {
			$compiledTemplateDir = $templateDir . '/compiled';
		}
		$this->compiledTemplateDir = realpath($compiledTemplateDir);
		
		if ($cacheDir == null) {
			$cacheDir = $templateDir . '/cache';
		}
		$this->cacheDir = realpath($cacheDir);
		
		if ($configDir == null) {
			$configDir = $templateDir;
		}
		$this->configDir = realpath($configDir);	
	}
	
	public function getName()
	{
		return 'Smarty Template (http://smarty.php.net) setup';
	}
	
	public function getPriority()
	{
		return 10;
	}

	public function setup01TemplateDirectory()
	{
		if (! Util::setupDirectory($this->templateDir)) {
			throw new Exception('Invalid directory');
		}
		return true;
	}

	public function setup02CompiledTemplateDirectory()
	{
		if (! Util::setupDirectory($this->compiledTemplateDir)) {
			throw new Exception('Invalid directory');
		}
		return true;
	}

	public function setup03CacheDirectory()
	{
		if (! Util::setupDirectory($this->cacheDir)) {
			throw new Exception('Invalid directory');
		}
		return true;
	}

	public function setup04ConfigDirectory()
	{
		if (! Util::setupDirectory($this->configDir)) {
			throw new Exception('Invalid directory');
		}
		return true;
	}
}
?>
