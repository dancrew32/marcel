<?
class controller_test extends controller_base {
	function __construct($o) {
		$this->root_path = route::get('User Home');
		parent::__construct($o);
   	}

	function craigslist() {
		$c = new craigslist(file_get_contents('https://gist.github.com/dancrew32/87d4e2a4eed4a8aae932/raw/ebedd30e9d4758e69c0ac155d6349e58d7bed010/gistfile1.txt'));
		die($c->get_inline_attributed());
		//pd(craigslist::get_restricted_html());		
	}

	function goodreads() {

		//$g = gmap::geocode('1 san francisco');
		//$location = $g->results[0];
		//pd($location);

		$user_id = 1;
		$user = goodreads::user($user_id)->user;
		$data = [
			'name' => (string) $user->name,
			'age' => (string) $user->age,
			'gender' => (string) $user->gender,
			'location' => (string) $user->location,
			//'lat' => $location->lat,
			//'lng' => $location->lng,
			'authors' => [],
		];

		$events = goodreads::events(94121);
		pd($events);

		foreach ($user->favorite_authors->author as $fa) {
			$author = goodreads::author($fa->id)->author;
			//pd($author);
			$out = [
				'name'       => (string) $author->name,
				'book_count' => (int) $author->words_count,
				'from'       => (string) $author->hometown,
				'born'       => strtotime($author->born_at),
				'died'       => strtotime(take($author, 'died_at', null)),
				'books'      => [],
			];
			$page = 1;
			$books = goodreads::author_books($author->id, $page);
			foreach ($books->author->books->book as $b) {
				$out['books'][] = [
					'id' => (int) $b->id,
					'title' => (string) $b->title,
					'published' => strtotime("{$b->publication_month}/{$b->publication_day}/{$b->publication_year}"),
					'image' => (string) preg_replace('#([0-9]{1})m/#', '$1l/', $b->image_url),
					'publisher' => $b->publisher,
				];
			}
			$data['authors'][] = $out;
		}
		pp($data);
	}
}
