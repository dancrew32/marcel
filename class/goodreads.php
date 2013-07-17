<?
class goodreads {
	const API = 'http://goodreads.com';
	const CACHE_TIME = time::ONE_HOUR;

	static function user($id) {
		$key = cache::keygen(__CLASS__, __FUNCTION__, $id);
		$data = cache::get($key, $found);
		if (!$found) {
			$remote = self::get(self::API."/user/show/{$id}.xml");
			if (!$remote->ok()) return false; 
			$data = $remote->get_data();
			cache::set($key, $data, self::CACHE_TIME);
		}
		return xml::parse($data);
	}

	static function author($id) {
		$key = cache::keygen(__CLASS__, __FUNCTION__, $id);
		$data = cache::get($key, $found);
		if (!$found) {
			$remote = self::get(self::API."/author/show/{$id}.xml");
			if (!$remote->ok()) return false; 
			$data = $remote->get_data();
			cache::set($key, $data, self::CACHE_TIME);
		}
		return xml::parse($data);
	}

	static function author_books($id, $page=1) {
		$key = cache::keygen(__CLASS__, __FUNCTION__, "{$id}_{$page}");
		$data = cache::get($key, $found);
		if (!$found) {
			$remote = self::get(self::API."/author/list/{$id}.xml", [
				'id'   => $id,
				'page' => $page,
			]);
			if (!$remote->ok()) return false; 
			$data = $remote->get_data();
			cache::set($key, $data, self::CACHE_TIME);
		}
		return xml::parse($data);
	}

	static function events($zipcode) {
		$remote = self::get(self::API."/event.xml", [
			'search[postal_code]' => $zipcode,
		]);
		if (!$remote->ok()) return false; 
		return xml::parse($remote->get_data());
	}

	static function get($url, array $params=[]) {
		$api = api::get_key('goodreads');
		$params = array_merge([
			'key' => take($api, 'key'),
		], $params);
		return remote::get($url, $params);
	}
}

