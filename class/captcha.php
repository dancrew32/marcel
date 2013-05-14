<?
class captcha {

	const KEY = '_CAPTCHA_CODE';

	static function generate(array $o=[]) {
		$o = array_merge([
			'code'            => '',
			'min_length'      => 5,
			'max_length'      => 5,
			'png_backgrounds' => [ IMAGE_DIR.'/captcha/default.png' ],
			'fonts'           => [ FONT_DIR.'/times_new_yorker.ttf' ],
			'characters'      => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
			'min_font_size'   => 24,
			'max_font_size'   => 30,
			'color'           => '#000',
			'angle_min'       => 0,
			'angle_max'       => 15,
			'shadow'          => true,
			'shadow_color'    => '#CCC',
			'shadow_offset_x' => -2,
			'shadow_offset_y' => 2
		], $o);
		
		self::random_delay();
		
		// Generate CAPTCHA code if not set by user
		if (empty($o['code'])) {
			$o['code'] = '';
			$length = rand($o['min_length'], $o['max_length']);
			while (strlen($o['code']) < $length)
				$o['code'] .= substr($o['characters'], rand() % (strlen($o['characters'])), 1);
		}
		
		
		return $o;
	}

	static function hex2rgb($hex_str, $return_string = false, $separator = ',') {
		$hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
		$rgb = [];
		if (strlen($hex_str) == 6) {
			$color_val = hexdec($hex_str);
			$rgb['r'] = 0xFF & ($color_val >> 0x10);
			$rgb['g'] = 0xFF & ($color_val >> 0x8);
			$rgb['b'] = 0xFF & $color_val;
		} elseif (strlen($hex_str) == 3) {
			$rgb['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
			$rgb['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
			$rgb['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
		} else
			return false;
		return $return_string ? implode($separator, $rgb) : $rgb;
	}

	static function random_delay() {
		srand(microtime() * 100);
	}

	static function test($code) {
		if (trim($code) == take($_SESSION, self::KEY)) {
			unset($_SESSION[self::KEY]);
			return true;
		}
		return false;
	}

	static function get(array $o=[]) {
		$o = self::generate($o);

		// Generate image src
		$_SESSION[self::KEY] = $o['code'];
		
		// Pick random background, get info, and start captcha
		$background = $o['png_backgrounds'][rand(0, count($o['png_backgrounds']) - 1)];
		list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);
		
		// Create captcha object
		$captcha = imagecreatefrompng($background);
		imagealphablending($captcha, true);
		imagesavealpha($captcha , true);
		
		$color = self::hex2rgb($o['color']);
		$color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);
			
		// Determine text angle
		$angle = rand( $o['angle_min'], $o['angle_max'] ) * (rand(0, 1) == 1 ? -1 : 1);
		
		// Select font randomly
		$font = $o['fonts'][rand(0, count($o['fonts']) - 1)];
		
		// Verify font file exists
		if (!is_file($font)) 
			throw new Exception('Font file not found: ' . $font);
		
		// Set the font size.
		$font_size = rand($o['min_font_size'], $o['max_font_size']);
		$text_box_size = imagettfbbox($font_size, $angle, $font, $o['code']);
		
		// Determine text position
		$box_width = abs($text_box_size[6] - $text_box_size[2]);
		$box_height = abs($text_box_size[5] - $text_box_size[1]);
		$text_pos_x_min = 0;
		$text_pos_x_max = ($bg_width) - ($box_width);
		$text_pos_x = rand($text_pos_x_min, $text_pos_x_max);			
		$text_pos_y_min = $box_height;
		$text_pos_y_max = ($bg_height) - ($box_height / 2);
		$text_pos_y = rand($text_pos_y_min, $text_pos_y_max);
		
		// Draw shadow
		if ($o['shadow']) {
			$shadow_color = self::hex2rgb($o['shadow_color']);
			$shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
			imagettftext(
				$captcha, 
				$font_size, 
				$angle, 
				$text_pos_x + $o['shadow_offset_x'], 
				$text_pos_y + $o['shadow_offset_y'], 
				$shadow_color, 
				$font, 
				$o['code']
			);	
		}
		
		// Draw text
		imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $o['code']);	
		
		return $captcha;
	}
}
