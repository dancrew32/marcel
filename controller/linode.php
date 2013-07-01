<?
class controller_linode extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('Linode Home');
		auth::only(['linode']);
		parent::__construct($o);
   	}

	function main() { }

	function linodes() {
		$this->linodes = linode::_list();
	}

	function linode($o) {
		$l = take($o, 'linode');
		$this->id     = take($l, 'LINODEID');
		$this->label  = take($l, 'LABEL', 'No Label');

		$this->status = take($l, 'STATUS', 0);
		$this->status_class = $this->status ? 'success' : 'important';
	}

	function domains($o) {
		$linode_id = take($o, 'linode_id');
		$this->domains = linode::domain_list(['LinodeID' => $linode_id]);
	}

	function domain($o) {
		$d = take($o, 'domain');
		$this->id = take($d, 'DOMAINID');

		$this->domain = take($d, 'DOMAIN');
		$this->domain_url = "http://{$this->domain}";

		$this->status = take($d, 'STATUS', 0);
		$this->status_class = $this->status ? 'success' : 'important';
	}

	function resources($o) {
		$domain_id = take($o, 'domain_id');
		$this->resources = linode::resource_list(['DomainID' => $domain_id]);
	}

	function resource($o) {
		$r = take($o, 'resource');
		$this->id = take($r, 'RESOURCEID');
		$this->name = take($r, 'NAME');
		$this->target = take($r, 'TARGET');
		$this->port = take($r, 'PORT');
		$this->type = take($r, 'TYPE');
	}

	function plans() {
		$this->plans = linode::plans();
	}

	function plan($o) {
		$p = take($o, 'plan');

		$this->id = take($p, 'PLANID');
		$this->label = take($p, 'LABEL');
		$this->price = '$'.number_format(take($p, 'PRICE', 0), 2);
		$this->ram = take($p, 'RAM') ."MB";
		$this->disk = take($p, 'DISK') ."GB";
		$this->transfer = take($p, 'XFER') ."TB";
	}

	function balancers() {
		$this->balancers = linode::balance_list();
		// TODO: https://github.com/krmdrms/linode/issues/2
	}

	function balancer() {
		$b = take($o, 'balancer');
		// TODO: https://github.com/krmdrms/linode/issues/2
	}

}
