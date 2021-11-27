<?php 
//Courtesy: https://wordpress.stackexchange.com/users/35541/gmazzap
// require the class
@require_once __DIR__ . '/captcha.php';

// defaults
$w = 110;
$h = 35; 
$random_dots = 0;
$random_lines = 20;
$captcha_text_color = "0x#B9B098";
$captcha_noice_color = "0x#CEC7BA";

/* get code */
$code = filter_input( INPUT_GET, 'c', FILTER_SANITIZE_STRING );
if ( empty( $code ) ) die( '' );
$code = MyCaptcha::decode( $code );

/* get settings */
$sizes = filter_input( INPUT_GET, 'size', FILTER_SANITIZE_STRING );
if ( ! empty( $sizes ) ) {
  $s = explode( 'x', $sizes );
  if ( is_numeric($s[0]) && (int) $s[0] > 0  ) $w = (int) $s[0];
  if ( isset($s[1]) && is_numeric($s[1]) && (int) $s[1] > 0  ) $h = (int) $s[1];
}
$dots = (int) filter_input( INPUT_GET, 'dots', FILTER_SANITIZE_NUMBER_INT );
if ( $dots > 0 ) $random_dots = $dots;
$lines = (int) filter_input( INPUT_GET, 'lines', FILTER_SANITIZE_NUMBER_INT );
if ( $dots > 0 ) $random_lines = $lines;
$tcolor = (string) filter_input( INPUT_GET, 'tcolor', FILTER_SANITIZE_STRING );
if ( strlen($tcolor) === 6 ) $captcha_text_color = "0x#{$tcolor}";
$ncolor = (string) filter_input( INPUT_GET, 'ncolor', FILTER_SANITIZE_STRING );
if ( strlen($ncolor) === 6 ) $captcha_noice_color = "0x#{$ncolor}";

/* prepare image */
$font = './monofont.ttf';
$font_size = $h * 0.75;
$image = @imagecreate($w, $h);
$bg_color = imagecolorallocate($image, 255, 255, 255);
$arr_text_color = MyCaptcha::hexrgb($captcha_text_color);
$text_color = imagecolorallocate(
  $image,
  $arr_text_color['red'], 
  $arr_text_color['green'],
  $arr_text_color['blue']
);
$arr_noice_color = MyCaptcha::hexrgb($captcha_noice_color);
  $image_noise_color = imagecolorallocate(
  $image,
  $arr_noice_color['red'], 
  $arr_noice_color['green'],
  $arr_noice_color['blue']
);

/* generating dots randomly in background of image */
for( $i=0; $i < $random_dots; $i++ ) {
  imagefilledellipse(
    $image,
    mt_rand(0, $w), mt_rand(0, $h),
    2, 3,
    $image_noise_color
  );
}

/* generating lines randomly in background of image */
for( $i=0; $i < $random_lines; $i++ ) {
  imageline(
    $image,
    mt_rand(0, $w), mt_rand(0, $h),
    mt_rand(0, $w), mt_rand(0, $h),
    $image_noise_color
  );
}

/* create a text box and add 6 letters code in it */
$textbox = imagettfbbox( $font_size, 0, $font, $code ); 
$x = ( $w - $textbox[4] ) / 2;
$y = ( $h - $textbox[5] ) / 2;
imagettftext( $image, $font_size, 0, $x, $y, $text_color, $font , $code);

/* output captcha image */
header('Content-Type: image/jpeg');
imagejpeg($image);
imagedestroy($image); 
?>