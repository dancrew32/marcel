<?
class ocr {
	# apt-get install tesseract-ocr
	# Usage:tesseract imagename outputbase [-l lang] [-psm pagesegmode] [configfile...]
	# http://tesseract-ocr.googlecode.com/svn/trunk/doc/tesseract.1.html
	# TODO: define image partitions for sub-analysis

	#0 = Orientation and script detection (OSD) only.
	const ORIENTATION_SCRIPT_ONLY = 0;
	#1 = Automatic page segmentation with OSD.
	const AUTO_PAGE_SEG_OSD = 1;
	#2 = Automatic page segmentation, but no OSD, or OCR
	const AUTO_PAGE_SEG_NO_OSD = 2;
	#3 = Fully automatic page segmentation, but no OSD. (Default)
	const FULL_AUTO_NO_OSD = 3;
	#4 = Assume a single column of text of variable sizes.
	const SINGLE_COLUMN_VARIABLE_SIZE = 4;
	#5 = Assume a single uniform block of vertically aligned text.
	const UNIFORM_BLOCK_VERTICAL = 5;	
	#6 = Assume a single uniform block of text.
	const UNIFORM_BLOCK = 6;	
	#7 = Treat the image as a single text line.
	const SINGLE_LINE = 7;
	#8 = Treat the image as a single word.
	const SINGLE_WORD = 8;
	#9 = Treat the image as a single word in a circle.
	const SINGLE_WORD_CIRCLE = 9;
	#10 = Treat the image as a single character.
	const SINGLE_CHAR = 10;

	static function get($file_path) {

		$ocr_dir = TMP_DIR.'/ocr'; 
		$rand = rand();

		# Whitelist
		$whitelist  = implode('', range('a', 'z'));
		$whitelist .= implode('', range('A', 'Z'));
		$whitelist .= implode('', range(0, 9));

		$cmd = "tessedit_char_whitelist {$whitelist}";
		$conf = "{$ocr_dir}/config.{$rand}.conf";
		file_put_contents($conf, $cmd);

		# Flatten image
		$flat = "{$ocr_dir}/flat.{$rand}.png";
		$cmd = "convert {$file_path} -colorspace gray +matte {$flat}";
		exec($cmd);

		# Interpret file
		$outfile = "{$ocr_dir}/out";
		# -l lang and/or -psm pagesegmode must occur before anyconfigfile.
		$cmd = "tesseract {$flat} {$outfile} -l eng -psm 8 nobatch {$conf} 2> /dev/null";
		exec($cmd);

		$outfile .= ".txt";
		$out = file_get_contents($outfile);

		array_map('unlink', [$conf, $flat, $outfile]);

		return $out;
	}	
}
