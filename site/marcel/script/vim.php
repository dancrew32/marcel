<?
require_once(dirname(__FILE__).'/inc.php');
$output_file = TMP_DIR.'/vim-output.php';
system("vim {$output_file} > `tty`"); 
$data = file_get_contents($output_file);
$data = preg_replace('/^.+\n/', '', $data); # remove <? 
ob_start();
require_once(dirname(__FILE__).'/inc.php');
eval($data);
$out = ob_get_contents();
ob_end_clean();
echo $out;
