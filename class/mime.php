<?
class mime {

	private static $application = [
		'dwg'     => 'application/acad',                        
		'ez'      => 'application/andrew-inset',                
		'ccad'    => 'application/clariscad',                   
		'drw'     => 'application/drafting',                    
		'tsp'     => 'application/dsptype',                     
		'dxf'     => 'application/dxf',                         
		'evy'     => 'application/envoy',                       
		'fif'     => 'application/fractals',                    
		'spl'     => 'application/futuresplash',                
		'hta'     => 'application/hta',                         
		'unv'     => 'application/i-deas',                      
		'acx'     => 'application/internet-property-stream',    
		'jar'     => 'application/java-archive',                
		'json'    => 'application/json',                        
		'hqx'     => 'application/mac-binhex40',                
		'cpt'     => 'application/mac-compactpro',              
		'doc'     => 'application/msword',                      
		'dot'     => 'application/msword',                      
		'bin'     => 'application/octet-stream',                
		'class'   => 'application/octet-stream',                
		'dms'     => 'application/octet-stream',                
		'exe'     => 'application/octet-stream',                
		'lha'     => 'application/octet-stream',                
		'lzh'     => 'application/octet-stream',                
		'oda'     => 'application/oda',                         
		'ogm'     => 'application/ogg',                         
		'axs'     => 'application/olescript',                   
		'pdf'     => 'application/pdf',                         
		'pgp'     => 'application/pgp',                         
		'prf'     => 'application/pics-rules',                  
		'p10'     => 'application/pkcs10',                      
		'crl'     => 'application/pkix-crl',                    
		'ai'      => 'application/postscript',                  
		'eps'     => 'application/postscript',                  
		'ps'      => 'application/postscript',                  
		'prt'     => 'application/pro_eng',                     
		'rtf'     => 'application/rtf',                         
		'set'     => 'application/set',                         
		'setpay'   => 'application/set-payment-initiation',      
		'setreg'   => 'application/set-registration-initiation', 
		'stl'     => 'application/SLA',                         
		'smil'    => 'application/smil',                        
		'sol'     => 'application/solids',                      
		'stp'     => 'application/STEP',                        
		'vda'     => 'application/vda',                         
		'xla'     => 'application/vnd.ms-excel',                
		'xlc'     => 'application/vnd.ms-excel',                
		'xlm'     => 'application/vnd.ms-excel',                
		'xls'     => 'application/vnd.ms-excel',                
		'xlt'     => 'application/vnd.ms-excel',                
		'xlw'     => 'application/vnd.ms-excel',                
		'sst'     => 'application/vnd.ms-pkicertstore',         
		'cat'     => 'application/vnd.ms-pkiseccat',            
		'stl'     => 'application/vnd.ms-pkistl',               
		'pot'     => 'application/vnd.ms-powerpoint',           
		'pps'     => 'application/vnd.ms-powerpoint',           
		'ppt'     => 'application/vnd.ms-powerpoint',           
		'ppz'     => 'application/vnd.ms-powerpoint',           
		'mpp'     => 'application/vnd.ms-project',              
		'wcm'     => 'application/vnd.ms-works',                
		'wdb'     => 'application/vnd.ms-works',                
		'wks'     => 'application/vnd.ms-works',                
		'wps'     => 'application/vnd.ms-works',                
		'cod'     => 'application/vnd.rim.cod',                 
		'hlp'     => 'application/winhlp',                      
		'arj'     => 'application/x-arj-compressed',            
		'bcpio'   => 'application/x-bcpio',                     
		'cdf'     => 'application/x-cdf',                       
		'vcd'     => 'application/x-cdlink',                    
		'pgn'     => 'application/x-chess-pgn',                 
		'z'       => 'application/x-compress',                  
		'tgz'     => 'application/x-compressed',                
		'cpio'    => 'application/x-cpio',                      
		'csh'     => 'application/x-csh',                       
		'deb'     => 'application/x-debian-package',            
		'dcr'     => 'application/x-director',                  
		'dir'     => 'application/x-director',                  
		'dxr'     => 'application/x-director',                  
		'dvi'     => 'application/x-dvi',                       
		'pre'     => 'application/x-freelance',                 
		'spl'     => 'application/x-futuresplash',              
		'gtar'    => 'application/x-gtar',                      
		'gz'      => 'application/x-gzip',                      
		'hdf'     => 'application/x-hdf',                       
		'ins'     => 'application/x-internet-signup',           
		'isp'     => 'application/x-internet-signup',           
		'iii'     => 'application/x-iphone',                    
		'ipx'     => 'application/x-ipix',                      
		'ips'     => 'application/x-ipscript',                  
		'js'      => 'application/javascript',                
		'skt'     => 'application/x-koan',                      
		'latex'   => 'application/x-latex',                     
		'lsp'     => 'application/x-lisp',                      
		'scm'     => 'application/x-lotusscreencam',            
		'mif'     => 'application/x-mif',                       
		'mdb'     => 'application/x-msaccess',                  
		'crd'     => 'application/x-mscardfile',                
		'clp'     => 'application/x-msclip',                    
		'com'     => 'application/x-msdos-program',             
		'dll'     => 'application/x-msdownload',                
		'm13'     => 'application/x-msmediaview',               
		'm14'     => 'application/x-msmediaview',               
		'mvb'     => 'application/x-msmediaview',               
		'wmf'     => 'application/x-msmetafile',                
		'mny'     => 'application/x-msmoney',                   
		'pub'     => 'application/x-mspublisher',               
		'scd'     => 'application/x-msschedule',                
		'trm'     => 'application/x-msterminal',                
		'wri'     => 'application/x-mswrite',                   
		'nc'      => 'application/x-netcdf',                    
		'pma'     => 'application/x-perfmon',                   
		'pmc'     => 'application/x-perfmon',                   
		'pml'     => 'application/x-perfmon',                   
		'pmr'     => 'application/x-perfmon',                   
		'pmw'     => 'application/x-perfmon',                   
		'pm'      => 'application/x-perl',                      
		'php'     => 'application/x-httpd-php',	
		'p12'     => 'application/x-pkcs12',                    
		'pfx'     => 'application/x-pkcs12',                    
		'p7b'     => 'application/x-pkcs7-certificates',        
		'spc'     => 'application/x-pkcs7-certificates',        
		'p7r'     => 'application/x-pkcs7-certreqresp',         
		'p7c'     => 'application/x-pkcs7-mime',                
		'p7m'     => 'application/x-pkcs7-mime',                
		'p7s'     => 'application/x-pkcs7-signature',           
		'rar'     => 'application/x-rar-compressed',            
		'sh'      => 'application/x-sh',                        
		'shar'    => 'application/x-shar',                      
		'swf'     => 'application/x-shockwave-flash',           
		'sit'     => 'application/x-stuffit',                   
		'sv4cpio' => ',application/x-sv4cpio',
		'sv4crc'  => ',application/x-sv4crc',
		'tar'     => 'application/x-tar',                       
		'tgz'     => 'application/x-tar-gz',                    
		'tcl'     => 'application/x-tcl',                       
		'tex'     => 'application/x-tex',                       
		'texi'    => 'application/x-texinfo',                   
		'texinfo' => ',application/x-texinfo',
		'roff'    => 'application/x-troff',                     
		't'       => 'application/x-troff',                     
		'tr'      => 'application/x-troff',                     
		'man'     => 'application/x-troff-man',                 
		'me'      => 'application/x-troff-me',                  
		'ms'      => 'application/x-troff-ms',                  
		'ustar'   => 'application/x-ustar',                     
		'src'     => 'application/x-wais-source',               
		'cer'     => 'application/x-x509-ca-cert',              
		'crt'     => 'application/x-x509-ca-cert',              
		'der'     => 'application/x-x509-ca-cert',              
		'pko'     => 'application/ynd.ms-pkipko',               
		'zip'     => 'application/zip',                         
	];

	private static $audio = [
		'au'   => 'audio/basic',                         
		'snd'  => 'audio/basic',                         
		'mid'  => 'audio/mid',                           
		'rmi'  => 'audio/mid',                           
		'midi' => 'audio/midi',                          
		'mp3'  => 'audio/mpeg',                          
		'mpga' => 'audio/mpeg',                          
		'tsi'  => 'audio/TSP-audio',                     
		'au'   => 'audio/ulaw',                          
		'aif'  => 'audio/x-aiff',                        
		'aifc' => 'audio/x-aiff',                        
		'aiff' => 'audio/x-aiff',                        
		'm3u'  => 'audio/x-mpegurl',                     
		'wax'  => 'audio/x-ms-wax',                      
		'wma'  => 'audio/x-ms-wma',                      
		'ra'   => 'audio/x-pn-realaudio',                
		'ram'  => 'audio/x-pn-realaudio',                
		'rm'   => 'audio/x-pn-realaudio',                
		'rpm'  => 'audio/x-pn-realaudio-plugin',         
		'ra'   => 'audio/x-realaudio',                   
		'wav'  => 'audio/x-wav',                         
		'xyz'  => 'chemical/x-pdb',                      
	];

	private static $image = [
		'bmp'  => 'image/bmp',                              
		'cod'  => 'image/cis-cod',                            
		'gif'  => 'image/gif',                                
		'ief'  => 'image/ief',                                
		'jpe'  => 'image/jpeg',                               
		'jpeg' => 'image/jpeg',                               
		'jpg'  => 'image/jpeg',                               
		'jfif' => 'image/pipeg',                              
		'png'  => 'image/png',                                
		'svg'  => 'image/svg+xml',                            
		'tif'  => 'image/tiff',                               
		'tiff' => 'image/tiff',                               
		'ras'  => 'image/x-cmu-raster',                       
		'cmx'  => 'image/x-cmx',                              
		'ico'  => 'image/x-icon',                             
		'pnm'  => 'image/x-portable-anymap',                  
		'pbm'  => 'image/x-portable-bitmap',                  
		'pgm'  => 'image/x-portable-graymap',                 
		'ppm'  => 'image/x-portable-pixmap',                  
		'rgb'  => 'image/x-rgb',                              
		'xbm'  => 'image/x-xbitmap',                          
		'xpm'  => 'image/x-xpixmap',                          
		'xwd'  => 'image/x-xwindowdump',                      
	];

	private static $message = [
		'mht'   => 'message/rfc822',
		'mhtml' => 'message/rfc822',
		'nws'   => 'message/rfc822',
	];

	private static $model = [
		'igs'  => 'model/iges',
		'silo' => 'model/mesh',
		'wrl'  => 'model/vrml',
	];

	private static $text = [
		'css'   => 'text/css',                                
		'323'   => 'text/h323',                               
		'htm'   => 'text/html',                               
		'html'  => 'text/html',                               
		'stm'   => 'text/html',                               
		'uls'   => 'text/iuls',                               
		'bas'   => 'text/plain',                              
		'c'     => 'text/plain',                              
		'h'     => 'text/plain',                              
		'm'     => 'text/plain',                              
		'txt'   => 'text/plain',                              
		'rtx'   => 'text/richtext',                           
		'rtf'   => 'text/rtf',                                
		'sct'   => 'text/scriptlet',                          
		'sgml'  => 'text/sgml',                               
		'tsv'   => 'text/tab-separated-values',               
		'jad'   => 'text/vnd.sun.j2me.app-descriptor',        
		'htt'   => 'text/webviewhtml',                        
		'htc'   => 'text/x-component',                        
		'etx'   => 'text/x-setext',                           
		'vcf'   => 'text/x-vcard',                            
		'xml'   => 'text/xml',                                
	];

	private static $video = [
		'dl'    => 'video/dl',
		'flv'   => 'video/flv',
		'gl'    => 'video/gl',
		'mp2'   => 'video/mpeg',
		'mpa'   => 'video/mpeg',
		'mpe'   => 'video/mpeg',
		'mpeg'  => 'video/mpeg',
		'mpg'   => 'video/mpeg',
		'mpv2'  => 'video/mpeg',
		'mov'   => 'video/quicktime',
		'qt'    => 'video/quicktime',
		'vivo'  => 'video/vnd.vivo',
		'fli'   => 'video/x-fli',
		'lsf'   => 'video/x-la-asf',
		'lsx'   => 'video/x-la-asf',
		'asf'   => 'video/x-ms-asf',
		'asr'   => 'video/x-ms-asf',
		'asx'   => 'video/x-ms-asf',
		'asx'   => 'video/x-ms-asx',
		'wmv'   => 'video/x-ms-wmv',
		'wmx'   => 'video/x-ms-wmx',
		'wvx'   => 'video/x-ms-wvx',
		'avi'   => 'video/x-msvideo',
		'movie' => 'video/x-sgi-movie',
	];

	private static $other = [
		'mime' => 'www/mime',
		'ice'  => 'x-conference/x-cooltalk',
		'flr'  => 'x-world/x-vrml',
		'vrm'  => 'x-world/x-vrml',
		'vrml' => 'x-world/x-vrml',
		'wrl'  => 'x-world/x-vrml',
		'wrz'  => 'x-world/x-vrml',
		'xaf'  => 'x-world/x-vrml',
		'xof'  => 'x-world/x-vrml',
	];

	static function application() {
		return self::$application;
	}

	static function audio() {
		return self::$audio;
	}

	static function image() {
		return self::$image;
	}

	static function message() {
		return self::$message;
	}

	static function model() {
		return self::$model;
	}

	static function video() {
		return self::$video;
	}

	static function other() {
		return self::$other;
	}

	static function is($extension, $type=null) {
		if ($type) {
			foreach (self::$type() as $k => $v) {
				if ($k != $extension) continue;
				return $v;
			}
		} else {
			foreach (get_class_vars(__CLASS__) as $mimes) {
				foreach ($mimes as $k => $v) {
					if ($k != $extension) continue;
					return $v;
				}
			}
		}
		return false;
	}

}
