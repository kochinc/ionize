<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 1.0.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize Resource Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	ACL
 * @author		Ionize Dev Team
 *
 */

class resource_model extends Base_model
{
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->table =		'resource';
		$this->pk_name = 	'id_resource';
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns the resource tree
	 *
	 * @return array
	 */
	public function get_tree()
	{
		$resources = $this->get_list();

		$tree = $this->build_resources_tree($resources);

		return $tree;
	}


	// ------------------------------------------------------------------------


	/**
	 * Builds the resource tree
	 *
	 * @param array $elements
	 * @param null  $id_parent
	 * @param int   $level
	 *
	 * @return array
	 */
	public function build_resources_tree(array &$elements, $id_parent = NULL, $level=0)
	{
		$branch = array();

		foreach ($elements as $element)
		{
			// $id_parent can be a string,
			// Correct PHP buggy 'string' == 0
			// log_message('error', $id_parent.' : '.gettype($id_parent) . ' / ' . $element['id_parent'].' : '.gettype($element['id_parent']));
			// if ( $element['id_parent'] === 0) $element['id_parent'] = '';

			if ($element['id_parent'] == $id_parent)
			{
				$element['level'] = $level;
				$children = $this->build_resources_tree($elements, $element['id_resource'], $level+1);

				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}

		return $branch;
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns all the available resources,
	 * DB + Modules, merged in one array.
	 *
	 * @return array
	 *
	 */
	public function get_all_resources()
	{
		$resources = $this->get_list();

		$modules_resources = Modules()->get_resources();

		return array_merge($resources, $modules_resources);
	}

}
