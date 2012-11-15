<?php


/**
 * Description of GroupsController
 *
 * @author thainn
 */
class GroupsController extends AppController {

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('*'));
	}
	
	function add() {
		if (!empty($this->data)) {
			$this->Group->create();
			if ($this->Group->save($this->data)) {
				$this->Session->setFlash(__('The group has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.', true));
			}
		}
	}
	
	function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->paginate());
	}


	function permission(){
		if ($this->request->is('post')) {
		 	$this->_setPermission();
		}
		$this->_initPermission();
	}

	function _setPermission() {
		$rules = array();
		$this->Acl->Aco->unbindModel(array('hasAndBelongsToMany' => array('Aro')));
		$rules_temp = $this->Acl->Aco->find('all');
		foreach($rules_temp as $item){
			$rules[$item['Aco']['id']] = $item;
		}

		$permissions = $this->request->data['permission'];
		foreach($permissions as $key=>$permission){
			$sql = 'delete from  aros_acos WHERE aro_id =' . $key;
			$this->Acl->Aco->query($sql);
			
			$group = $this->Group;
			$group->id = $key; 
			
			$parent_id  	= 0;
			$run_deny_all 	= 1;
			
			foreach($permission as $permission_key=>$permission_value){
				$level = 2; 
				$rolename = $this->_parseToPermissionName($rules, $permission_value, $level);
				if($level == 0){
					$this->Acl->allow($group, $rolename);
					break;
				}elseif($level == 1){
					if($run_deny_all) $this->Acl->deny($group, 'controllers'); //using white list, deny all permission first.
					
					$parent_id 		= $permission_value;
					$run_deny_all 	= 0;
					
					$this->Acl->allow($group, $rolename);
					continue;
				}else{
					if($rules[$permission_value]['Aco']['parent_id'] == $parent_id) {
						continue;
					}
					$this->Acl->allow($group, $rolename);
				}
			}
		}
	}
	
	function _parseToPermissionName($rules = array(), $ruleId = 0, &$level){
		if(!$rules || !$ruleId) return '';
		
		$rule = $rules[$ruleId]['Aco'];
		if(!$rule) return '';
		
		if(is_null($rule['parent_id'])) $level = 0;
		elseif($rule['parent_id'] == 1) $level = 1;
		
		if($level < 2) return $rule['alias'];
		$rulename = '';
		$parent_name = $rules[$rule['parent_id']]['Aco']['alias'];
		
		return $parent_name.'/'.$rule['alias'];
	}
	
	function _initPermission(){
		$groups = $this->Group->find('all');
		
		$temp_permission = $this->Acl->Aco->find('all');
		$permission = array();
		
		foreach($temp_permission as $item){
			$temp = array();
		
			foreach($item['Aro'] as $group) {
				$final_permission = 0;
		
				$flag = $group['Permission']['_create'] + $group['Permission']['_read'] +
				$group['Permission']['_update'] + $group['Permission']['_delete'];
		
				if($flag > 0)
					$final_permission = 1;
		
				$temp[$group['foreign_key']] = $final_permission;
			}
			$permission[$item['Aco']['id']] = $temp;
		}
		
		$this->Acl->Aco->unbindModel(array('hasAndBelongsToMany' => array('Aro')));
		$rules = $this->Acl->Aco->find('all');
		
		$this->Acl->Aro->unbindModel(array('hasAndBelongsToMany' => array('Aco')));
		$arostemp = $this->Acl->Aro->find('all');
		$aros = array();
		foreach($arostemp as $item){
			if($item['Aro']['model'] == 'Group')
				$aros[$item['Aro']['foreign_key']] = $item;
		}
		
		$this->set('rules', $rules);
		$this->set('aros', $aros);
		$this->set('groups', $groups);
		$this->set('permission', $permission);
		
// 		print "<pre>";
// 		print_r($aros);
// 		print "</pre>";
	}
	
	function build_acl() {
		if (!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();
		$aco =& $this->Acl->Aco;
		$root = $aco->node('controllers');
		if (!$root) {
			$aco->create(array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id;
			$log[] = 'Created Aco node for controllers';
		} else {
			$root = $root[0];
		}
		App::import('Core', 'File');
		$Controllers = App::objects('controller');
		
		// we need the name, not the fullname, so trim 'Controller' from it.
		foreach($Controllers as $key=>$value){
			$Controllers[$key] = str_replace('Controller', '', $value);
		}
	
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'build_acl';
		
		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);
		
		// look at each controller in app/controllers
		foreach ($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));
			
			// Do all Plugins First
			if ($this->_isPlugin($ctrlName)){
				$pluginNode = $aco->node('controllers/'.$this->_getPluginName($ctrlName));
				if (!$pluginNode) {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/'.$ctrlName);
			if (!$controllerNode) {
				if ($this->_isPlugin($ctrlName)){
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create(array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				} else {
					$aco->create(array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			} else {
				$controllerNode = $controllerNode[0];
			}
			//clean the methods. to remove those in Controller and private actions.
			if($methods)
			foreach ($methods as $k => $method) {
				if (strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue;
				}
				if (in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue;
				}
				$methodNode = $aco->node('controllers/'.$ctrlName.'/'.$method);
				if (!$methodNode) {
					$aco->create(array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for '. $method;
				}
			}
		}
		if(count($log)>0) {
			debug($log);
		}
		print "<pre>";
		print_r('game over');
		print "</pre>";
		exit;
	}
	
	function _getClassMethods($ctrlName = null) {
		App::import('Controller', $ctrlName);
		
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num + 1);
		}
		$ctrlclass =  $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);
		
		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if($properties)
		if (array_key_exists('scaffold', $properties) && $properties['scaffold']) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			} else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}
	
	function _isPlugin($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) > 1) {
			return true;
		} else {
			return false;
		}
	}
	function _getPluginControllerPath($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		} else {
			return $arr[0];
		}
	}
	function _getPluginName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}
	function _getPluginControllerName($ctrlName = null) {
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}
	/**
	 * Get the names of the plugin controllers ...
	 *
	 * This function will get an array of the plugin controller names, and
	 * also makes sure the controllers are available for us to get the
	 * method names by doing an App::import for each plugin controller.
	 *
	 * @return array of plugin names.
	 *
	 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');

		App::uses('File', 'Utility');
		App::uses('Folder', 'Utility');
		
		$pluginPath = APP . 'Plugin';
		$folder =& new Folder();
		$folder->cd($pluginPath);
		// Get the list of plugins
		$Plugins = $folder->read();
		
		
		$Plugins = $Plugins[0];
		$arr = array();
		
		
		
		// Loop through the plugins
		if($Plugins)
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd($pluginPath . DS . $pluginName . DS . 'Controller');
			if(!$didCD) continue;
			
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = array();
			$files = $folder->findRecursive('.*Controller\.php');
			
			
			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('Controller.php')));
				
				
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		
		return $arr;
	}
	
}