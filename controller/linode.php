<?
class controller_linode extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Linode Home');
		auth::only(['linode']);
		parent::__construct($o);
   	}

	function main() {
		//$api_key = linode::api_key([
			//'username' => 'username',
			//'password' => 'password',
		//]);
		//echo '<h3>Linodes</h3>';
		//$linodes = linode::_list();
		//pp($linodes);

		//echo '<h3>Domains</h3>';
		//$domains = linode::domain_list();
		//pp(array_map(function($domain) {
			//return [$domain['DOMAINID'], $domain['DOMAIN']];
		//}, $domains));

		//echo '<h3>Resources</h3>';
		//$resources = linode::resource_list([
			//'DomainID' => 111111,
		//]);
		//pp(array_map(function($resource) {
			//return [ 
				//"{$resource['TARGET']}:{$resource['PORT']}", 
				//"{$resource['NAME']} ({$resource['TYPE']})"
			//];
		//}, $resources));

		//echo '<h3>Balancers</h3>';
		//$balancers = linode::balance_list();
		//pp($balancers);

		//echo '<h3>Community Scripts</h3>';
		//$public_scripts = linode::scripts();
		//echo '<pre>';
		//pr(array_map(function($script) {
			//return $script['LABEL'];
		//}, $public_scripts));
		//echo '</pre>';

		//echo '<h3>Your Scripts</h3>';
		//$scripts = linode::script_list();
		//pp($scripts);

		//echo '<h3>Your Scripts</h3>';
		//$scripts = linode::script_list();
		//pp($scripts);
		
		
	}
}
