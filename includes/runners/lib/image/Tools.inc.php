<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->2/24/2013 17:39:03<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Tools.inc.php
   * @version 
   * @revision 
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */
  class Tools
  {

  			var $VERSION = "WxWeb Image Tools.php 3.15 2/24/2013 17:38:56<br />";


  			function __construct($debug, $form, $conf)
  			{


  						$this->conf = $conf;
  						$this->debug = $debug;
  						$this->form = $form;

  						if ($this->debug)
  									echo $this->VERSION;

  			}


  			function do_title($title,$credit,$image)
  			{

  						$size = imagesx($image);
if ($this->debug) print_r($this->conf);
  						$barcolor = (!empty($this->conf['Title Settings'][$size . 'titlebarcolor'])) ? $this->conf['Title Settings'][$size .
  									'titlebarcolor'] : '701122';
  						$barheight = (!empty($this->conf['Title Settings'][$size . 'barheight'])) ? $this->conf['Title Settings'][$size . 'barheight'] :
  									'30';
  						$bartrans = (!empty($this->conf['Title Settings'][$size . 'bartrans'])) ? $this->conf['Title Settings'][$size . 'bartrans'] : '80';
  						$linethick = (!empty($this->conf['Title Settings'][$size . 'linethick'])) ? $this->conf['Title Settings'][$size . 'linethick'] :
  									'1';
  						$linecolor = (!empty($this->conf['Title Settings'][$size . 'linecolor'])) ? $this->conf['Title Settings'][$size . 'linecolor'] :
  									'FFFFFF';
  						// 1 means draw
  						$bardraw = (isset($this->conf['Title Settings'][$size . 'barimgdraw'])) ? $this->conf['Title Settings'][$size . 'barimgdraw'] :
  									'1';
  						$barimg = (!empty($this->conf['Title Settings'][$size . 'barimg'])) ? $this->conf['Title Settings'][$size . 'barimg'] : '';
  						$barimgpath = (!empty($this->conf['Title Settings'][$size . 'barimgpath'])) ? $this->conf['Title Settings'][$size . 'barimgpath'] :
  									WXSITE . "/images";
  						$barimgx = (!empty($this->conf['Title Settings'][$size . 'barimgx'])) ? $this->conf['Title Settings'][$size . 'barimgx'] : '0';
  						$barimgy = (!empty($this->conf['Title Settings'][$size . 'barimgy'])) ? $this->conf['Title Settings'][$size . 'barimgy'] : '0';
  						$baralpha = (!empty($this->conf['Title Settings'][$size . 'baralpha'])) ? $this->conf['Title Settings'][$size . 'baralpha'] : '0';


  						$fontpath = (!empty($this->conf['Title Settings']['TTF_path'])) ? $this->conf['Title Settings']['TTF_path'] : TTFPATH;

  						$titlefont = (!empty($this->conf['Title Settings'][$size . 'title_TTF_font'])) ? $this->conf['Title Settings'][$size .
  									'title_TTF_font'] : 'arialbd.ttf';
  						$titlex = (!empty($this->conf['Title Settings'][$size . 'titlex'])) ? $this->conf['Title Settings'][$size . 'titlex'] : '5';
  						$titley = (!empty($this->conf['Title Settings'][$size . 'titley'])) ? $this->conf['Title Settings'][$size . 'titley'] : '20';
  						$titlefontsize = (!empty($this->conf['Title Settings'][$size . 'title_TTF_pt'])) ? $this->conf['Title Settings'][$size .
  									'title_TTF_pt'] : '13';
  						$titlecolor = (!empty($this->conf['Title Settings']['titlecolor'])) ? $this->conf['Title Settings']['titlecolor'] : 'FFFFFF';

  						$creditfont = (!empty($this->conf['Title Settings'][$size . 'credit_TTF_font'])) ? $this->conf['Title Settings'][$size .
  									'credit_TTF_font'] : 'arialbd.ttf';
  						$screditx = (!empty($this->conf['Title Settings'][$size . 'creditx'])) ? $this->conf['Title Settings'][$size . 'creditx'] : '520';
  						$scredity = (!empty($this->conf['Title Settings'][$size . 'credity'])) ? $this->conf['Title Settings'][$size . 'credity'] : '20';
  						$creditfontsize = (!empty($this->conf['Title Settings'][$size . 'credit_TTF_pt'])) ? $this->conf['Title Settings'][$size .
  									'credit_TTF_pt'] : '13';
  						$creditcolor = (!empty($this->conf['Title Settings']['creditcolor'])) ? $this->conf['Title Settings']['creditcolor'] : 'FFFFFF';
  						$credit = (!empty($credit)) ? $credit : $this->conf['Title Settings']['credittext'];

  						$title = (!empty($title)) ? $title : $this->conf['Title Settings']['titletext'];

  						
  						if ($bardraw)
  						{
  									$barw = imagesx($image);

  									imagesetthickness($image, $linethick);
  									list($r, $g, $b) = $this->getRGB($barcolor);
  									$bar = imagecreatetruecolor($barw, $barheight);
  									$bgc = imagecolorallocate($bar, $r, $g, $b);
  									imagefilledrectangle($bar, 0, 0, $barw, $barheight, $bgc);
  									imagecopymerge($image, $bar, 0, 0, 0, 0, $barw, $barheight, $bartrans);


  									list($r, $g, $b) = $this->getRGB($linecolor);
  									$white = imagecolorallocate($image, $r, $g, $b);
  									imageline($image, 0, $barheight, $barw, $barheight, $white);
  						} else
  						{

  									//	imagesetthickness($image, '2');
  									$insert = imagecreatefrompng($barimgpath . $barimg);
  									$insertWidth = imagesx($insert);
  									$insertHeight = imagesy($insert);
  									if ($baralpha)
  									{
  												ImageAlphaBlending($image, true);
  												imagecopy($image, $insert, $barimgx, $barimgy, 0, 0, $insertWidth, $insertHeight);
  									} else
  									{
  												imagecopymerge($image, $insert, $barimgx, $barimgy, 0, 0, $insertWidth, $insertHeight, 100);
  									}

  						} //end else for draw
  						list($cr, $cg, $cb) = $this->getRGB($creditcolor);
  						list($tr, $tg, $tb) = $this->getRGB($titlecolor);
  						$black = imagecolorallocate($image, 0, 0, 0);
  						$crcolor = imagecolorallocate($image, $cr, $cg, $cb);
  						$ticolor = imagecolorallocate($image, $tr, $tg, $tb);

  						// _text_width_height($text,$fontPath,$fontFile,$fontSize,$fontAngle)
  						list($w, $h) = $this->_text_width_height($credit, $fontpath, $creditfont, $creditfontsize, '0');

  						list($creditx, $credity) = $this->_text_alignment(imagesx($image), imagesy($image), imagesx($image) - 8, $scredity, $w, $h,
  									'RIGHT');

  						imagettftext($image, $titlefontsize, 0, $titlex + 1, $titley + 1, $black, $fontpath . $titlefont, $title);


  						imagettftext($image, $creditfontsize, 0, $creditx + 1, $credity + 1, $black, $fontpath . $creditfont, $credit);

  						imagettftext($image, $titlefontsize, 0, $titlex, $titley, $ticolor, $fontpath . $titlefont, $title);


  						imagettftext($image, $creditfontsize, 0, $creditx, $credity, $crcolor, $fontpath . $creditfont, $credit);


  						return $image;

  			}


  			function doWatermark($image, $parms)
  			{

  						$watermark_path = (!empty($this->conf['Watermark Settings']['watermark_path'])) ? $this->conf['Watermark Settings']['watermark_path'] :
  									WATERMARKPATH;

  						list($mimage, $mx, $my, $alpha) = explode("|", $parms);

  						if (strtolower($alpha) == 'true' || is_numeric($alpha))
  						{
  									$alpha = $alpha;
  						} else
  						{
  									$alpha = '100';
  						}

  						list($mimg, $exttype) = explode(".", $mimage);

  						if ($exttype == 'jpg' || $exttype == 'jpeg')
  						{
  									$insert_img = imagecreatefromjpeg($watermark_path . '/' . $mimage);
  						} elseif ($exttype == 'png')
  						{
  									$insert_img = imagecreatefrompng($watermark_path . '/' . $mimage);
  						} elseif ($exttype == 'gif')
  						{
  									$insert_img = imagecreatefromgif($watermark_path . '/' . $mimage);
  						}

  						if (strtoupper($mx) == 'RIGHT')
  						{
  									$inx = imagesx($image) - imagesx($insert_img) - 2;
  						}
  						if (strtoupper($mx) == 'LEFT')
  						{
  									$inx = '2';
  						} elseif (is_numeric($mx))
  						{
  									$inx = $mx;
  						}

  						if (strtoupper($my) == 'BOTTOM')
  						{
  									$iny = imagesy($image) - imagesy($insert_img) - 2;
  						}
  						if (strtoupper($mx) == 'TOP')
  						{
  									$iny = '2';
  						} elseif (is_numeric($my))
  						{
  									$iny = $my;
  						}


  						if (strtolower($alpha) == 'true')
  						{
  									ImageAlphaBlending($image, true);
  									imagecopy($image, $insert_img, $inx, $iny, 0, 0, imagesx($insert_img), imagesy($insert_img));

  						} else
  						{
  									imagecopymerge($image, $insert_img, $inx, $iny, 0, 0, imagesx($insert_img), imagesy($insert_img), $alpha);
  						}


  						return $image;
  			}


  			function do_text($image, $text, $x, $y, $chest)
  			{

  						$outline = (!empty($chest['dsoutline'])) ? $chest['dsoutline'] : '0';
                        
                        $halign = (!empty($chest['halign'])) ? $chest['halign'] : '0';


  						list($r, $g, $b) = $this->getRGB($chest['ttfcolor']);
  						$fhex = imagecolorallocate($image, $r, $g, $b);

  						list($r, $g, $b) = $this->getRGB($chest['dscolor']);
  						$dshex = imagecolorallocate($image, $r, $g, $b);
                        
                        $chest['ttfpath'] = (isset($chest['ttfpath'])) ? $chest['ttfpath'] : TTFPATH;



  						$sy = imagesy($image);
                        
                        if (strtoupper($halign) == 'SPACEWRAP')
                        {
                            $lines = preg_split("/\s/", $text);
                            $image = Tools::DoSpacewrap($image,$lines,$x,$y,$chest);
                            return $image;
                            break;
                        }
                        
                        
  						list($text_w, $text_h) = $this->_text_width_height($text, $chest['ttfpath'], $chest['ttffont'], $chest['ttfpt'], $chest['ttfangle']);


  						list($ux, $uy) = $this->_text_alignment(imagesx($image), imagesy($image), $x, $y, $text_w, $text_h, $halign);


  						if ($outline)
  						{

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + 1 + $chest['xoff'], $uy + 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + 1 + $chest['xoff'], $uy + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux - 1 + $chest['xoff'], $uy + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy - 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);


  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + $chest['yoff'], $fhex, $chest['ttfpath'] . $chest['ttffont'],
  												$text);

  						} else
  						{
  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'] + $chest['dsxoff'], $uy + $chest['yoff'] + $chest['dsyoff'],
  												$dshex, $chest['ttfpath'] . $chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + $chest['yoff'], $fhex, $chest['ttfpath'] . $chest['ttffont'],
  												$text);
  						}


  						return $image;
  			}
            
            
            function DoSpacewrap($image, $lines, $x, $y, $chest)
  			{

  						$outline = (!empty($chest['dsoutline'])) ? $chest['dsoutline'] : '0';
                        
                        $halign = (!empty($chest['halign'])) ? $chest['halign'] : '0';


  						list($r, $g, $b) = $this->getRGB($chest['ttfcolor']);
  						$fhex = imagecolorallocate($image, $r, $g, $b);

  						list($r, $g, $b) = $this->getRGB($chest['dscolor']);
  						$dshex = imagecolorallocate($image, $r, $g, $b);



  						$sy = imagesy($image);
                        
                        foreach($lines as $text)
                        {
                        if ($this->debug) echo "SPACEWRAP $text\n";
                        
  						list($text_w, $text_h) = $this->_text_width_height($text, $chest['ttfpath'], $chest['ttffont'], $chest['ttfpt'], $chest['ttfangle']);


  						list($ux, $uy) = $this->_text_alignment(imagesx($image), imagesy($image), $x, $y, $text_w, $text_h, 'CENTER');


  						if ($outline)
  						{

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + 1 + $chest['xoff'], $uy + 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + 1 + $chest['xoff'], $uy + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux - 1 + $chest['xoff'], $uy + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy - 1 + $chest['yoff'], $dshex, $chest['ttfpath'] .
  												$chest['ttffont'], $text);


  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + $chest['yoff'], $fhex, $chest['ttfpath'] . $chest['ttffont'],
  												$text);

  						} else
  						{
  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'] + $chest['dsxoff'], $uy + $chest['yoff'] + $chest['dsyoff'],
  												$dshex, $chest['ttfpath'] . $chest['ttffont'], $text);

  									imagettftext($image, $chest['ttfpt'], $chest['ttfangle'], $ux + $chest['xoff'], $uy + $chest['yoff'], $fhex, $chest['ttfpath'] . $chest['ttffont'],
  												$text);
  						}
                        $y += ($text_h + 1);
                        }


  						return $image;
  			}

  			function do_nodata($image)
  			{

  						$size = imagesx($image);

  						$chest['ttfpath'] = (!empty($this->conf['Label Settings']['TTF_path'])) ? $this->conf['Label Settings']['TTF_path'] : TTFPATH;
  						$chest['ttffont'] = (!empty($this->conf['Label Settings']['TTF_font'])) ? $this->conf['Label Settings']['TTF_font'] :
  									'arialbd.ttf';
  						$chest['ttfpt'] = (!empty($this->conf['Label Settings']['TTF_font_pt'])) ? $this->conf['Label Settings']['TTF_font_pt'] : '10';
  						$chest['ttfangle'] = (!empty($this->conf['Label Settings']['TTF_font_angle'])) ? $this->conf['Label Settings']['TTF_font_angle'] :
  									'0';
  						$chest['ttfcolor'] = (!empty($this->conf['Label Settings']['font_color'])) ? $this->conf['Label Settings']['font_color'] :
  									'FFFFFF';
  						$chest['dscolor'] = (!empty($this->conf['Label Settings']['ds_color'])) ? $this->conf['Label Settings']['ds_color'] : '000000';
  						$chest['dsxoff'] = (!empty($this->conf['Label Settings']['ds_xoffset'])) ? $this->conf['Label Settings']['ds_xoffset'] : '1';
  						$chest['dsyoff'] = (!empty($this->conf['Label Settings']['ds_yoffset'])) ? $this->conf['Label Settings']['ds_yoffset'] : '1';
  						$chest['dsoutline'] = (!empty($this->conf['Label Settings']['ds_outline'])) ? $this->conf['Label Settings']['ds_outline'] : '0';

  						$chest['xoff'] = '0';
  						$chest['yoff'] = '0';

  						$line = $this->conf['Labels']['nodata'];

  						list($label, $xx, $yy, $chest['ttfpt'], $chest['ttfcolor'], $chest['dsxoff'], $chest['dsyoff']) = explode("|", $line);


  						$image = $this->do_text($image, $label, $xx, $yy, $chest);

  						return $image;
  			}

  			/*
  			global functions 
  			*/

  			function getRGB($color)
  			{
  						$hex = '';
  						$r = substr($color, 0, 2);
  						$g = substr($color, 2, 2);
  						$b = substr($color, 4, 2);

  						$r = hexdec($r);
  						$g = hexdec($g);
  						$b = hexdec($b);

  						return array(
  									$r,
  									$g,
  									$b);
  			}


  			function _text_width_height($text, $fontPath, $fontFile, $fontSize, $fontAngle)
  			{

  						$rect = imagettfbbox($fontSize, $fontAngle, $fontPath . '/' . $fontFile, $text);
  						$minX = min(array(
  									$rect[0],
  									$rect[2],
  									$rect[4],
  									$rect[6]));
  						$maxX = max(array(
  									$rect[0],
  									$rect[2],
  									$rect[4],
  									$rect[6]));
  						$minY = min(array(
  									$rect[1],
  									$rect[3],
  									$rect[5],
  									$rect[7]));
  						$maxY = max(array(
  									$rect[1],
  									$rect[3],
  									$rect[5],
  									$rect[7]));
  						$width = $maxX - $minX;
  						$height = $maxY - $minY;

  						return array($width, $height);
  			}


  			function _text_alignment($width, $height, $x, $y, $w, $h, $align)
  			{


  						if (strtoupper($y) == 'CENTER')
  						{
  									$y = floor(($height - $h) * .5);
  						} elseif (strtoupper($y) == 'BOTTOM')
  						{
  									$y = ($height - $h) - 12;
  						} elseif (preg_match('/\{h - ([\d]+)\}/', $y, $m))
  						{
  									$y = ($height - $h) - $m[1];
  						}

  						if (strtolower($x) == 'center')
  						{
  									$x = floor(($width - $w) / 2);
  						} 
                        elseif (strtoupper($align) == 'CENTER')
  						{
  									$x -= floor($w / 2);
                                    
  						} elseif (strtoupper($align) == 'RIGHT')
  						{
  									$x -= $w;
  						}
                        
                       //echo "final x,y $x,$y\n";


  						return array($x, $y);
  			}


  			function add_overlay($image)
  			{

  						$overlays = (!empty($this->conf['Image Settings']['overlays'])) ? $this->conf['Image Settings']['overlays'] : '';
                        
                        $addover = (!empty($this->conf['Image Settings']['addoverlays'])) ? $this->conf['Image Settings']['addoverlays'] : '1';
                        
                        if ($addover == '1')
                        {
                            if ($this->debug)
                            {
                                echo "<br />adding overlays<br />";
                            }

  						$overlays = explode("|", $overlays);

  						foreach ($overlays as $overlay)
  						{
  									list($over, $trans) = explode(":", $overlay);
                                    $tover = preg_replace('/\{(\w+)\}/e' , "\$this->conf['Image Settings']['$1']", $over);
                                    if (isset($this->form['map'])){
                                    $bover = preg_replace('/\{(\w+)\}/e' , "\$this->form['$1']", $over);
  									if (preg_match('/[\w\d]+_overlay/', $bover) || preg_match('/[\w\d]+_linesonly/', $bover) || preg_match('/[\w\d]+_counties/', $bover))
                                    {
                                        $tover = $bover;
                                        
                                    }
                                    }
                                    $timage = $this->load_image($tover);

  									imagecopymerge($image, $timage, 0, 0, 0, 0, imagesx($timage), imagesy($timage), $trans);

  						}
                        
                        }

  						return $image;
  			}
            
            
              			function add_underlay($image)
  			{

  						$underlays = (!empty($this->conf['Image Settings']['underlays'])) ? $this->conf['Image Settings']['underlays'] : '';
                        
                        $addunder = (!empty($this->conf['Image Settings']['addunderlays'])) ? $this->conf['Image Settings']['addunderlays'] : '0';
                        
                        if ($addunder == '1')
                        {
                            if ($this->debug)
                            {
                                echo "<br />adding underlays<br />";
                            }

  						$underlays = explode("|", $underlays);

  						foreach ($underlays as $underlay)
  						{
  									list($under, $trans) = explode(":", $underlay);
                                    $tunder = preg_replace('/\{(\w+)\}/e' , "\$this->conf['Image Settings']['$1']", $under);
                                    if (isset($this->form['map'])){
                                    $bunder = preg_replace('/\{(\w+)\}/e' , "\$this->form['$1']", $under);
  									if (preg_match('/[\w\d]+_overlay/', $bover) || preg_match('/[\w\d]+_linesonly/', $bover) || preg_match('/[\w\d]+_counties/', $bover))
                                    {
                                        $tunder = $bunder;
                                        
                                    }
                                    }
                                    $timage = $this->load_image($tunder);

  									imagecopymerge($image, $timage, 0, 0, 0, 0, imagesx($timage), imagesy($timage), $trans);

  						}
                        
                        }

  						return $image;
  			}


  			function save_image($image, $name)
  			{


  						$usemap = (!empty($this->form['map'])) ? $this->form['map'] : $this->conf['Image Settings']['map'];
  						$filepath = (!empty($this->conf['Image Settings']['save_path'])) ? $this->conf['Image Settings']['save_path'] : IMAGESAVEPATH;
  						$fileurl = (!empty($this->conf['Image Settings']['save_url'])) ? $this->conf['Image Settings']['save_url'] : IMAGESAVEURL;
  						$savetype = (!empty($this->conf['Image Settings']['save_type'])) ? $this->conf['Image Settings']['save_type'] : IMAGESAVETYPE;
  					
                      	
  						$savename = (!empty($this->form['name'])) ? $this->form['name'] : $name;

                        
                        
                        
                        
                        
                        $saveage = (!empty($this->conf['Image Settings']['save_age'])) ? $this->conf['Image Settings']['save_age'] : '15';

  						$nocache = (!empty($this->conf['Image Settings']['nocache'])) ? '1' : (!empty($this->form['nocache'])) ? '1' : '0';


  						$savename = (empty($savename)) ? $this->form['run'] . "_" . time() . "_" . $usemap : $savename;


  						$saveage = $saveage * 60;


  						if ($this->debug)
  						{
  									echo "<br />SaveName: $savename<br />Save age $saveage<br />";
  									echo "image age " . time() - @filectime($filepath . "/" . $savename . "." . $savetype) . "<br />";
  									echo "<br />Savepath: $filepath<br />Save url $fileurl<br />";
  						}


  						if (file_exists($filepath . "/" . $savename . "." . $savetype) && time() - @filectime($filepath . "/" . $savename . "." . $savetype) <
  									$saveage && !$nocache)
  						{
  									header("Location:" . $fileurl . "/" . $savename . "." . $savetype);
  									return;
  						} else
  						{

  									if ($savetype == 'gif')
  									{
  												imagegif($image, $filepath . "/" . $savename . ".gif");
  												header("Location:" . $fileurl . "/" . $savename . "." . $savetype);
  												return;
  									}

  									if ($savetype == 'png')
  									{
  												imagepng($image, $filepath . "/" . $savename . ".png");
  												header("Location:" . $fileurl . "/" . $savename . "." . $savetype);
  												return;
  									}

  									if ($savetype == 'jpeg')
  									{
  												imagejpeg($image, $filepath . "/" . $savename . ".jpeg");
  												header("Location:" . $fileurl . "/" . $savename . "." . $savetype);
  												return;
  									}
  						}


  			}


  			function load_image($image)
  			{


  						$filepath = (!empty($this->conf['Image Settings']['blankmaps'])) ? $this->conf['Image Settings']['blankmaps'] : IMAGESAVEPATH;

  						$exttype = (!empty($this->conf['Image Settings']['save_type'])) ? $this->conf['Image Settings']['save_type'] : IMAGESAVETYPE;


  						if ($exttype == 'gif')
  						{
  									$newimage = imagecreatefromgif($filepath . "/" . $image . ".gif");

  						}

  						if ($exttype == 'png')
  						{
  									$newimage = imagecreatefrompng($filepath . "/" . $image . ".png");
                                   

  						}

  						if ($exttype == 'jpeg')
  						{
  									$newimage = imagecreatefromjpeg($filepath . "/" . $image . ".jpeg");
                                   

  						}

  						return $newimage;


  			}
  			function save_thumb($image, $name, $filepath, $savetype)
  			{

 						$savename = (empty($name)) ? $this->form['run'] . "_" . time() . "_" . $this->usemap . "_thumb" : $name;


  						$saveage = 20 * 60;

  						$nocache = (!empty($this->conf['Image Settings']['nocache'])) ? '1' : (!empty($this->form['nocache'])) ? '1' : '0';


  						if ($this->debug)
  						{
  									echo "<br />SaveName: $savename \n $saveage\n $savetype\n";
  									echo "image age " . time() - @filectime($filepath . "/" . $savename . "." . $savetype) . "<br />";
  						}


  						if (file_exists($filepath . "/" . $savename . "." . $savetype) && time() - filectime($filepath . "/" . $savename . "." . $savetype) <
  									$saveage && $nocache)
  						{
  									
  									return;
  						} else
  						{

  									if ($savetype == 'gif')
  									{
  												imagegif($image, $filepath . "/" . $savename . ".gif");
  												return;
  									}

  									if ($savetype == 'png')
  									{
  												imagepng($image, $filepath . "/" . $savename . ".png");
  											
  												return;
  									}

  									if ($savetype == 'jpeg')
  									{
  												imagejpeg($image, $filepath . "/" . $savename . ".jpeg");
  											
  											//	return;
  									}
 						}


  			}
            
            Function resize($icon,$newx,$newy,$type){
                
            $new = imagecreatetruecolor($newx,$newy);
            
            if($type == "gif" or $type == "png"){
            imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
            imagealphablending($new, false);
            imagesavealpha($new, true);
            }

            imagecopyresampled($new, $icon, 0, 0, 0, 0, $newx, $newy,imagesx($icon),imagesy($icon));
            return $new;
            
    }


  } //end class


?>