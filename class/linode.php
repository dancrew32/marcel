<?
class linode {

	# https://www.linode.com/api/
	private static $error_codes = [
		0  => 'ok',
		1  => 'Bad request',
		2  => 'No action was requested',
		3  => 'The requested class does not exist',
		4  => 'Authentication failed',
		5  => 'Object not found',
		6  => 'A required property is missing for this action',
		7  => 'Property is invalid',
		8  => 'A data validation error has occurred',
		9  => 'Method Not Implemented',
		10 => 'Too many batched requests',
		11 => 'RequestArray isn\'t valid JSON or WDDX',
		12 => 'Batch approaching timeout. Stopping here.',
		13 => 'Permission denied',
		14 => 'API rate limit exceeded',
		30 => 'Charging the credit card failed',
		31 => 'Credit card is expired',
		40 => 'Limit of Linodes added per hour reached',
		41 => 'Linode must have no disks before delete',
	];

	private static function cache_key($function, $id) {
		return cache::keygen(__CLASS__, $function, $id);
	}

	static function init() {
		//require_once VENDOR_DIR.'/linode/Linode.php';
		require_once 'Services/Linode.php';
		$api = api::get_key('linode');
		$key = take($api, 'key', null);
		return new Services_Linode($key);
	}


/*
 * ACCOUNT
 */
	# https://www.linode.com/api/utility/account.info
	static function account() {
		$l = self::init();
		return $l->account_info();
	}

	# https://www.linode.com/api/utility/user.getapikey
	static function api_key(array $o=[]) {
		$o = array_merge([
			'username' => null, # req
			'password' => null, # req
		], $o);

		$l = self::init();
		return self::get_data($l->user_getapikey($o))['API_KEY'];
	}


/*
 * AVAILABILITY
 */
	# https://www.linode.com/api/utility/avail.linodeplans
	static function plans(array $o=[]) {
		$o = array_merge([
			'PlanID' => null,
		], $o);

		$key = self::cache_key(__FUNCTION__, $o['PlanID']);
		$data = cache::get($key, $found, true);
		if (!$found) {
			$l = self::init();
			$data = self::get_data($l->avail_linodeplans($o));
			cache::set($key, $data, time::ONE_DAY, true);
		}
		return $data;
	}

	# https://www.linode.com/api/utility/avail.datacenters
	static function datacenters(array $o=[]) {
		$o = array_merge([
			'PlanID' => null,
		], $o);

		$l = self::init();
		return $l->avail_datacenters($o);
	}

	# https://www.linode.com/api/utility/avail.distributions
	static function distros(array $o=[]) {
		$o = array_merge([
			'DistributionID' => null,
		], $o);

		$l = self::init();
		return $l->avail_distributions($o);
	}

	# https://www.linode.com/api/utility/avail.kernels
	static function kernels(array $o=[]) {
		$o = array_merge([
			'KernelID' => null,
			'isXen'    => null,
		], $o);

		$l = self::init();
		return $l->avail_kernels($o);
	}

	# https://www.linode.com/api/utility/avail.stackscripts
	static function scripts(array $o=[]) {
		$o = array_merge([
			'DistributionID'     => null,
			'DistributionVendor' => '',
			'keywords'           => '',
		], $o);

		$l = self::init();

		return self::get_data($l->avail_stackscripts($o));
	}


/*
 * DNS (DOMAINS)
 */
	# https://www.linode.com/api/dns/domain.create
	static function domain_create(array $o=[]) {
		$o = array_merge([
			'Domain'      => '', # site.com (req)
			'Description' => '',
			'Type'        => 'master', # or slave
			'SOA_Email'   => ADMIN_EMAIL,
			'Refresh_sec' => 0,
			'Retry_sec'   => 0,
			'Expire_sec'  => 0,
			'TTL_sec'     => 0,
			'status'      => 1, # 0:disabled, 1:active, 2:edit
			'master_ips'  => '', # if Type=slave, master DNS server list: ip:ip2:ip3a
			'axfr_ips'    => '', # ip:ip2:ip3 (allowed to AXFR entire zone)
		], $o);

		$l = self::init();
		return $l->domain_create($o);
	}

	# https://www.linode.com/api/dns/domain.delete
	static function domain_delete(array $o=[]) {
		$o = array_merge([
			'DomainID' => null, # req
		], $o);

		$l = self::init();
		return $l->domain_delete($o);
	}

	# https://www.linode.com/api/dns/domain.list
	static function domain_list(array $o=[]) {
		$o = array_merge([
			'DomainID' => null,
		], $o);

		$key = self::cache_key(__FUNCTION__, $o['DomainID']);
		$data = cache::get($key, $found, true);
		if (!$found) {
			$l = self::init();
			$data = self::get_data($l->domain_list($o));
			cache::set($key, $data, time::ONE_MINUTE, true);
		}
		return $data;
	}

	# https://www.linode.com/api/dns/domain.update 
	static function domain_update(array $o=[]) {
		$o = array_merge([
			'DomainID'    => null, # req
			'Domain'      => '', # site.com
			'Description' => '',
			'Type'        => 'master', # or slave
			'SOA_Email'   => ADMIN_EMAIL,
			'Refresh_sec' => 0,
			'Retry_sec'   => 0,
			'Expire_sec'  => 0,
			'TTL_sec'     => 0,
			'status'      => 1, # 0:disabled, 1:active, 2:edit
			'master_ips'  => '', # if Type=slave, master DNS server list: ip:ip2:ip3a
			'axfr_ips'    => '', # ip:ip2:ip3 (allowed to AXFR entire zone)
		], $o);

		$l = self::init();
		return $l->domain_update($o);
	}


/*
 * DOMAIN RESOURCE
 */
	# https://www.linode.com/api/dns/domain.resource.create
	static function resource_create(array $o=[]) {
		$o = array_merge([
			'DomainID'    => null, # req
			'Type'        => '', # req (NS, MX, A, AAAA, CNAME, TXT, or SRV)
			'Name'        => '', # hostname or FQDN. if Type=MX, subdomain to delegate to Target MX server
			'Target'      => '', # If Type=MX, hostname. If Type=CNAME, target of alias. If Type=TXT, record value. If Type=A or AAAA, remote_addr token substituted with request IP
			'Priority'    => 10, # 0-255. MX & SRV record priority
			'Weight'      => 5,
			'Port'        => 80, 
			'Protocol'    => 'udp', # if Type=SRV
			'TTL_sec'     => 0,
		], $o);

		$l = self::init();
		return $l->domain_resource_create($o);
	}

	# https://www.linode.com/api/dns/domain.resource.delete
	static function resource_delete(array $o=[]) {
		$o = array_merge([
			'DomainID'   => null, # req
			'ResourceID' => null, # req
		], $o);

		$l = self::init();
		return $l->domain_resource_delete($o);
	}

	# https://www.linode.com/api/dns/domain.resource.list
	static function resource_list(array $o=[]) {
		$o = array_merge([
			'DomainID'   => null, # req
			'ResourceID' => null,
		], $o);

		$key = self::cache_key(__FUNCTION__, "{$o['DomainID']}::{$o['ResourceID']}");
		$data = cache::get($key, $found, true);
		if (!$found) {
			$l = self::init();
			$data = self::get_data($l->domain_resource_list($o));
			cache::set($key, $data, time::ONE_MINUTE * 15, true);
		}
		return $data;
	}

	# https://www.linode.com/api/dns/domain.resource.update
	static function resource_update(array $o=[]) {
		$o = array_merge([
			'DomainID'    => null, # req
			'ResourceID'  => null, # req
			'Type'        => '', # req (NS, MX, A, AAAA, CNAME, TXT, or SRV)
			'Name'        => '', # hostname or FQDN. if Type=MX, subdomain to delegate to Target MX server
			'Target'      => '', # If Type=MX, hostname. If Type=CNAME, target of alias. If Type=TXT, record value. If Type=A or AAAA, remote_addr token substituted with request IP
			'Priority'    => 10, # 0-255. MX & SRV record priority
			'Weight'      => 5,
			'Port'        => 80, 
			'Protocol'    => 'udp', # if Type=SRV
			'TTL_sec'     => 0,
		], $o);

		$l = self::init();
		return $l->domain_resource_create($o);
	}

/*
 * SCRIPTS (STACKSCRIPTS)
 */
	# https://www.linode.com/api/stackscript/stackscript.create
	static function script_create(array $o=[]) {
		$o = array_merge([
			'Label'              => null, # req (StackScript Label)
			'Description'        => '',
			'DistributionIDList' => '', # 1,3,4,5
			'isPublic'           => false, # community contributed
			'rev_note'           => '',
			'script'             => '', # the actual script contents
		], $o);

		$l = self::init();
		return $l->stackscript_create($o);
	}

	# https://www.linode.com/api/stackscript/stackscript.delete
	static function script_delete(array $o=[]) {
		$o = array_merge([
			'StackScriptID' => null, # req
		], $o);

		$l = self::init();
		return $l->stackscript_delete($o);
	}

	# https://www.linode.com/api/stackscript/stackscript.list
	static function script_list(array $o=[]) {
		$o = array_merge([
			'StackScriptID' => null,
		], $o);

		$l = self::init();
		return $l->stackscript_list($o);
	}

	# https://www.linode.com/api/stackscript/stackscript.update
	static function script_update(array $o=[]) {
		$o = array_merge([
			'StackScriptID'      => null, # req
			'Label'              => '', # StackScript Label
			'Description'        => '',
			'DistributionIDList' => '', # 1,3,4,5
			'isPublic'           => false, # community contributed
			'rev_note'           => '',
			'script'             => '', # the actual script contents
		], $o);

		$l = self::init();
		return $l->stackscript_update($o);
	}


/*
 * LOAD BALANCERS (NODEBALANCERS)
 */
	# https://www.linode.com/api/nodebalancer/nodebalancer.create
	static function balance_create(array $o=[]) {
		$o = array_merge([
			'DatacenterID' => null, # req
			'PaymentTerm'  => null, # req | months: 1, 12, 24
		], $o);

		$l = self::init();
		return $l->nodebalancer_create($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.delete
	static function balance_delete(array $o=[]) {
		$o = array_merge([
			'NodeBalancerID' => null, # req
		], $o);

		$l = self::init();
		return $l->nodebalancer_delete($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.list
	static function balance_list(array $o=[]) {
		$o = array_merge([
			'NodeBalancerID' => null,
		], $o);

		$l = self::init();
		return self::get_data($l->nodebalancer_list($o));
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.update
	static function balance_update(array $o=[]) {
		$o = array_merge([
			'NodeBalancerID'     => null, # req
			'Label'              => '',
			'ClientConnThrottle' => null, # 0:disable, connections/sec max of 20
		], $o);

		$l = self::init();
		return $l->nodebalancer_create($o);
	}


/*
 * LOAD BALANCER CONFIG (NODEBALANCER)
 */
	# https://www.linode.com/api/nodebalancer/nodebalancer.config.create
	static function balance_config_create(array $o=[]) {
		$o = array_merge([
			'NodeBalancerID' => null, # req
			'Port'           => 80,
			'Protocol'       => 'http', # or tcp
			'Algorithm'      => 'roundrobin', # leastconn, source
			'Stickiness'     => 'table', # or none, http_cookie (Session)
			'check'          => 'connection', # http or http_body
			'check_interval' => 5, # sec between health check (2-3600)
			'check_timeout'  => 3, # sec before probe failure (1-30, check_interval)
			'check_attempts' => 2, # fail probe before taking node out of rotation (1-30)
			'check_path'     => '/', # if check=http, path to request
			'check_body'     => '', # if check=http, regex against expected result body
		], $o);

		$l = self::init();
		return $l->nodebalancer_config_create($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.config.delete
	static function balance_config_delete(array $o=[]) {
		$o = array_merge([
			'ConfigID' => null, # req
		], $o);

		$l = self::init();
		return $l->nodebalancer_config_delete($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.config.list
	static function balance_config_list(array $o=[]) {
		$o = array_merge([
			'NodeBalancerID' => null, # req
			'ConfigID'       => null,
		], $o);

		$l = self::init();
		return $l->nodebalancer_config_list($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.config.update
	static function balance_config_update(array $o=[]) {
		$o = array_merge([
			'ConfigID'       => null, # req
			'Port'           => 80,
			'Protocol'       => 'http', # or tcp
			'Algorithm'      => 'roundrobin', # leastconn, source
			'Stickiness'     => 'table', # or none, http_cookie (Session)
			'check'          => 'connection', # http or http_body
			'check_interval' => 5, # sec between health check (2-3600)
			'check_timeout'  => 3, # sec before probe failure (1-30, check_interval)
			'check_attempts' => 2, # fail probe before taking node out of rotation (1-30)
			'check_path'     => '/', # if check=http, path to request
			'check_body'     => '', # if check=http, regex against expected result body
		], $o);

		$l = self::init();
		return $l->nodebalancer_config_update($o);
	}


/*
 * LOAD BALANCER NODE (NODEBALANCER)
 */
	# https://www.linode.com/api/nodebalancer/nodebalancer.node.create
	static function balance_node_create(array $o=[]) {
		$o = array_merge([
			'ConfigID' => null, # req
			'Label'    => '', # req
			'Address'  => '', # req ip:port used to communicate with this node
			'Weight'   => 100, # balancing weight, 1-255 (higher == more connections)
			'Mode'     => 'accept', # or reject or drain (connection mode for this node)
		], $o);

		$l = self::init();
		return $l->nodebalancer_node_create($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.node.delete
	static function balance_node_delete(array $o=[]) {
		$o = array_merge([
			'NodeID' => null, # req
		], $o);

		$l = self::init();
		return $l->nodebalancer_node_delete($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.node.list
	static function balance_node_list(array $o=[]) {
		$o = array_merge([
			'ConfigID' => null, # req
			'NodeID'   => null,
		], $o);

		$l = self::init();
		return $l->nodebalancer_node_list($o);
	}

	# https://www.linode.com/api/nodebalancer/nodebalancer.node.update
	static function balance_node_update(array $o=[]) {
		$o = array_merge([
			'NodeID'   => null, # req
			'Label'    => '', # req
			'Address'  => '', # req ip:port used to communicate with this node
			'Weight'   => 100, # balancing weight, 1-255 (higher == more connections)
			'Mode'     => 'accept', # or reject or drain (connection mode for this node)
		], $o);

		$l = self::init();
		return $l->nodebalancer_node_update($o);
	}


/*
 * LINODES
 */
	# https://www.linode.com/api/linode/linode.boot
	static function boot(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'ConfigID' => null,
		], $o);

		$l = self::init();
		return $l->linode_boot($o);
	}

	# https://www.linode.com/api/linode/linode.clone
	static function _clone(array $o=[]) {
		$o = array_merge([
			'LinodeID'     => null, # req (id to clone)
			'DatacenterID' => null, # req (from self::datacenters())
			'PlanID'       => null, # req (from self::plans())
			'PaymentTerm'  => null, # req months 1, 12, 24
		], $o);

		$l = self::init();
		return $l->linode_clone($o);
	}

	# https://www.linode.com/api/linode/linode.create
	static function create(array $o=[]) {
		$o = array_merge([
			'DatacenterID' => null, # req (from self::datacenters())
			'PlanID'       => null, # req (from self::plans())
			'PaymentTerm'  => null, # req months 1, 12, 24
		], $o);

		$l = self::init();
		return $l->linode_create($o);
	}

	# https://www.linode.com/api/linode/linode.delete
	static function delete(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
			'skipChecks'  => false, # true will delete
		], $o);

		$l = self::init();
		return $l->linode_delete($o);
	}

	# https://www.linode.com/api/linode/linode.list 
	static function _list(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null,
		], $o);

		$key = self::cache_key(__FUNCTION__, $o['LinodeID']);
		$data = cache::get($key, $found, true);
		if (!$found) {
			$l = self::init();
			$data = self::get_data($l->linode_list($o));
			cache::set($key, $data, time::ONE_DAY, true);
		}
		return $data;
	}

	# https://www.linode.com/api/linode/linode.reboot 
	static function reboot(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
			'ConfigID'    => null,
		], $o);

		$l = self::init();
		return $l->linode_reboot($o);
	}

	# https://www.linode.com/api/linode/linode.resize
	static function resize(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
			'PlanID'      => null, # req (from self::plans())
		], $o);

		$l = self::init();
		return $l->linode_resize($o);
	}

	# https://www.linode.com/api/linode/linode.shutdown
	static function shutdown(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
		], $o);

		$l = self::init();
		return $l->linode_shutdown($o);
	}

	# https://www.linode.com/api/linode/linode.update 
	static function update(array $o=[]) {
		$o = array_merge([
			'LinodeID'                => null, # req
			'Label'                   => '',
			'lpm_displayGroup'        => '',
			'Alert_cpu_enabled'       => true, # cpu
			'Alert_cpu_threshold'     => 90,   # 0-800
			'Alert_diskio_enabled'    => true, # disk io
			'Alert_diskio_threshold'  => 1000, # IO Ops/sec
			'Alert_bwin_enabled'      => true, # incoming bandwidth
			'Alert_bwin_threshold'    => 5,    # Mbit/sec
			'Alert_bwout_enabled'     => true, # outgoing bandwidth
			'Alert_bwout_threshold'   => 5,    # Mbit/sec
			'Alert_bwquota_enabled'   => true, # outgoing bandwidth
			'Alert_bwquota_threshold' => 80,   # % of network transfer quota
			'backupWindow'            => null, # TODO: https://manager.linode.com/linodes/backups_enable
			'backupWeeklyDay'         => null, # TODO: https://manager.linode.com/linodes/backups_enable
			'watchdog'                => true, # lassie shutdown watchdog
		], $o);

		$l = self::init();
		return $l->linode_update($o);
	}

/*
 * LINODE CONFIG
 */
	# https://www.linode.com/api/linode/linode.config.create
	static function config_create(array $o=[]) {
		$o = array_merge([
			'LinodeID'               => null, # req
			'KernelID'               => null, # req (from self::kernels())
			'Label'                  => '',   # req 
			'Comments'               => '',
			'RAMLimit'               => 0, # 0:max, in MB
			'DiskList'               => ',,,,,,,,', # ',,,,,,,,' position reflects device node, 9th el is initrd
			'RunLevel'               => 'default', # or single, binbash
			'RootDeviceNum'          => 1, # device number that contains the root partition (1-8), 0 is RootDeviceCustom
			'RootDeviceCustom'       => '', 
			'RootDeviceRO'           => true, # Enable 'ro' kernel flag (modern distros)
			'helper_disableUpdateDB' => true, # Enable disableUpdateDB filesystem helper
			'helper_xen'             => true, # Enable xen filesystem helper, corrects fstab and inittab/upstart
			'helper_depmod'          => true, # empty modprobe file for kernel you boot
			'devtmpfs_automount'     => true, # pv_ops kernels automount devtmpfs at boot
		], $o);

		$l = self::init();
		return $l->linode_config_create($o);
	}

	# https://www.linode.com/api/linode/linode.config.delete
	static function config_delete(array $o=[]) {
		$o = array_merge([
			'LinodeID'               => null, # req
			'ConfigID'               => null, # req
		], $o);

		$l = self::init();
		return $l->linode_config_delete($o);
	}

	# https://www.linode.com/api/linode/linode.config.list
	static function config_list(array $o=[]) {
		$o = array_merge([
			'LinodeID'               => null, # req
			'ConfigID'               => null,
		], $o);

		$l = self::init();
		return $l->linode_config_list($o);
	}

	# https://www.linode.com/api/linode/linode.config.update
	static function config_update(array $o=[]) {
		$o = array_merge([
			'LinodeID'               => null, # req
			'ConfigID'               => null, # req
			'KernelID'               => null, # req (from self::kernels())
			'Label'                  => '',   # req 
			'Comments'               => '',
			'RAMLimit'               => 0, # 0:max, in MB
			'DiskList'               => ',,,,,,,,', # ',,,,,,,,' position reflects device node, 9th el is initrd
			'RunLevel'               => 'default', # or single, binbash
			'RootDeviceNum'          => 1, # device number that contains the root partition (1-8), 0 is RootDeviceCustom
			'RootDeviceCustom'       => '', 
			'RootDeviceRO'           => true, # Enable 'ro' kernel flag (modern distros)
			'helper_disableUpdateDB' => true, # Enable disableUpdateDB filesystem helper
			'helper_xen'             => true, # Enable xen filesystem helper, corrects fstab and inittab/upstart
			'helper_depmod'          => true, # empty modprobe file for kernel you boot
			'devtmpfs_automount'     => true, # pv_ops kernels automount devtmpfs at boot
		], $o);

		$l = self::init();
		return $l->linode_config_update($o);
	}


/*
 * LINODE DISK
 */
	# https://www.linode.com/api/linode/linode.disk.create
	static function disk_create(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'Label'    => '', # req
			'Type'     => '', # req (ext3, swap, raw)
			'Size'     => '', # req (in MB)
		], $o);

		$l = self::init();
		return $l->linode_disk_create($o);
	}

	# https://www.linode.com/api/linode/linode.disk.createfromdistribution
	static function disk_create_from_distribution(array $o=[]) {
		$o = array_merge([
			'LinodeID'       => null, # req
			'DistributionID' => null, # req (from self::distros())
			'Label'          => '', # req
			'Size'           => '', # req (in MB)
			'rootPass'       => null, # req (root user password)
			'rootSSHKey'     => null, # req (puts string into /root/.ssh/authorized_keys on distro config)
		], $o);             

		$l = self::init();
		return $l->linode_disk_createfromdistribution($o);
	}

	# https://www.linode.com/api/linode/linode.disk.createfromstackscript
	static function disk_create_from_script(array $o=[]) {
		$o = array_merge([
			'LinodeID'                 => null, # req
			'StackScriptID'            => null, # req (from self::script_list())
			'StackScriptUDFResponses'  => '', # req (JSON name/value pairs, answering script user defined fields)
			'DistributionID'           => null, # req (from self::distros())
			'Label'                    => '', # req
			'Size'                     => '', # req (in MB)
			'rootPass'                 => null, # req (root user password)
		], $o);             

		$l = self::init();
		return $l->linode_disk_createfromstackscript($o);
	}

	# https://www.linode.com/api/linode/linode.disk.delete
	static function disk_delete(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'DiskID'   => null, # req
		], $o);

		$l = self::init();
		return $l->linode_disk_delete($o);
	}

	# https://www.linode.com/api/linode/linode.disk.duplicate
	static function disk_duplicate(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'DiskID'   => null, # req
		], $o);

		$l = self::init();
		return $l->linode_disk_duplicate($o);
	}

	# https://www.linode.com/api/linode/linode.disk.list
	static function disk_list(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'DiskID'   => null,
		], $o);

		$l = self::init();
		return $l->linode_disk_list($o);
	}

	# https://www.linode.com/api/linode/linode.disk.resize
	static function disk_resize(array $o=[]) {
		$o = array_merge([
			'LinodeID' => null, # req
			'DiskID'   => null, # req
			'size'     => null, # req (in MB)
		], $o);

		$l = self::init();
		return $l->linode_disk_resize($o);
	}

	# https://www.linode.com/api/linode/linode.disk.update
	static function disk_update(array $o=[]) {
		$o = array_merge([
			'LinodeID'   => null, # req
			'DiskID'     => null, # req
			'Label'      => '',   # req
			'isReadOnly' => null, # Enable read-only mode for disk
		], $o);

		$l = self::init();
		return $l->linode_disk_update($o);
	}


/*
 * IP ADDRESSES
 */
	# https://www.linode.com/api/linode/linode.ip.addprivate
	static function ip_add_private(array $o=[]) {
		$o = array_merge([
			'LinodeID'   => null, # req
		], $o);

		$l = self::init();
		return $l->linode_ip_addprivate($o);
	}

	# https://www.linode.com/api/linode/linode.ip.list
	static function ip_list(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
			'IPAddressID' => null,
		], $o);

		$l = self::init();
		return $l->linode_ip_list($o);
	}

/*
 * JOBS 
 */
	# https://www.linode.com/api/linode/linode.ip.list
	static function job_list(array $o=[]) {
		$o = array_merge([
			'LinodeID'    => null, # req
			'JobID'       => null,
			'pendingOnly' => null,
		], $o);

		$l = self::init();
		return $l->linode_job_list($o);
	}


/*
 * COMMON BATCH OPERATIONS
 */
	private static function batch_example() {
		try {
			$l = self::init();
			$l->batching = true;
			$l->linode_list();
			$l->domain_list();
			return $l->batchFlush();
		} catch(Services_Linode_Exception $e) {
			pd($e->getMessage());
		}
	}

	private static function get_data($o) {
		try {
			return $o['DATA'];
		} catch(Services_Linode_Exception $e) {
			throw $e;
		}
	}
}
