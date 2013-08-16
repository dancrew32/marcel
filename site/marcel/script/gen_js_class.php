<?
require_once(dirname(__FILE__).'/inc.php');

$config = config::$setting;

$ok = true;
$module_name = strtolower(gets("Enter Module Name: (e.g. Module_Name)"));

$boilerplate =';(function(NS, MODULE_NAME) {

	"use strict";

	var _class = function(options) { this.init(options); };

	NS.CLASSES[MODULE_NAME] = _class;

	_class.prototype = {
		init: function(options) {
			this.options = $.extend({
	
			}, options || {});
		}
	};

}(APP, \''. $module_name .'\'));';

$script_name = "{$module_name}.js";
$full_script_path = "{$config['public_dir']}{$config['js_dir']}/class/{$script_name}";

$exists = is_file($full_script_path);
if ($exists)
	return red("Module exists.\n");

$ok = file_put_contents($full_script_path, $boilerplate);
if ($ok) {
	green("Successfully created module: {$script_name}\n");
	green("<? app::asset('class/{$script_name}', 'js') ?>\n");
	green("var module = new APP.CLASSES.{$module_name}();\n");
}
else
	red("WRITE FAIL\n");
