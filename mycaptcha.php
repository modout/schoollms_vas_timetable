<?php
if( !function_exists('hex2rgb') ) {
	
    function hex2rgb($hex_str, $return_string = false, $separator = ',') {
        $hex_str = preg_replace("/[^0-9A-Fa-f]/", '', $hex_str); // Gets a proper hex string
        $rgb_array = array();
        if( strlen($hex_str) == 6 ) {
            $color_val = hexdec($hex_str);
            $rgb_array['r'] = 0xFF & ($color_val >> 0x10);
            $rgb_array['g'] = 0xFF & ($color_val >> 0x8);
            $rgb_array['b'] = 0xFF & $color_val;
        } elseif( strlen($hex_str) == 3 ) {
            $rgb_array['r'] = hexdec(str_repeat(substr($hex_str, 0, 1), 2));
            $rgb_array['g'] = hexdec(str_repeat(substr($hex_str, 1, 1), 2));
            $rgb_array['b'] = hexdec(str_repeat(substr($hex_str, 2, 1), 2));
        } else {
            return false;
        }
        return $return_string ? implode($separator, $rgb_array) : $rgb_array;
    }
}
$bg_path = dirname(__FILE__) . '/backgrounds/';
$backgrounds = array(
            $bg_path . '45-degree-fabric.png',
            $bg_path . 'cloth-alike.png',
            $bg_path . 'grey-sandbag.png',
            $bg_path . 'kinda-jean.png',
            $bg_path . 'polyester-lite.png',
            $bg_path . 'stitched-wool.png',
            $bg_path . 'white-carbon.png',
            $bg_path . 'white-wave.png'
        );
$background = $backgrounds[mt_rand(0, count($backgrounds) -1)];
list($bg_width, $bg_height, $bg_type, $bg_attr) = getimagesize($background);
		

extract($_GET);
/*$characters = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghjkmnprstuvwxyz23456789!@#$%^&*()_+<>?";
$code = "";
$length = mt_rand(5, 5);
while( strlen($code) < $length ) {
	$code .= substr($characters , mt_rand() % (strlen($characters )), 1);
}*/

$captcha = imagecreatefrompng("images/background.png");
imagealphablending($captcha, true); // set alpha blending on
imagesavealpha($captcha, true); // save alphablending setting (important)
$color = hex2rgb("#000");
$color = imagecolorallocate($captcha, $color['r'], $color['g'], $color['b']);
//var_dump($color);
$angle = mt_rand( 0, 10) * (mt_rand(0, 1) == 1 ? -1 : 1);
$fonts = array('fonts/times_new_yorker.ttf');
$font = $fonts[mt_rand(0, count($fonts) - 1)];
if( !file_exists($font) ) throw new Exception('Font file not found: ' . $font);
$font_size = mt_rand(28, 28);
$text_box_size = imagettfbbox($font_size, $angle, $font, $code);

// Determine text position
$box_width = abs($text_box_size[6] - $text_box_size[2]);
$box_height = abs($text_box_size[5] - $text_box_size[1]);
$text_pos_x_min = 0;
$text_pos_x_max = ($bg_width) - ($box_width);
$text_pos_x = mt_rand($text_pos_x_min, $text_pos_x_max);
$text_pos_y_min = $box_height;
$text_pos_y_max = ($bg_height) - ($box_height / 2);
if ($text_pos_y_min > $text_pos_y_max) {
	$temp_text_pos_y = $text_pos_y_min;
	$text_pos_y_min = $text_pos_y_max;
	$text_pos_y_max = $temp_text_pos_y;
}
$text_pos_y = mt_rand($text_pos_y_min, $text_pos_y_max);

$shadow_color = "#fff";

// Draw shadow
if( true ){
	$shadow_color = hex2rgb($shadow_color);
	$shadow_color = imagecolorallocate($captcha, $shadow_color['r'], $shadow_color['g'], $shadow_color['b']);
	imagettftext($captcha, $font_size, $angle, $text_pos_x + (-1), $text_pos_y + 1, $shadow_color, $font, $code);
}

// Draw text
imagettftext($captcha, $font_size, $angle, $text_pos_x, $text_pos_y, $color, $font, $code);
//echo $code;
// Output image
header("Content-type: image/png");
imagepng($captcha);

?>