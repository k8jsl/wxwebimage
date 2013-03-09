<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->2/24/2013 17:39:47<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Legend.inc.php
   * @version 3
   * @revision .54
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */

  class Legend
  {
  			var $VERSION = "WxWeb Image Legend.php 3.55<br />";

  			function __construct($debug, $form, $conf, $tools)
  			{

  						$this->tools = $tools;
  						$this->conf = $conf;
  						$this->debug = $debug;
  						$this->form = $form;

  						if ($this->debug)
  						{
  									echo $this->VERSION;
  						}
  			}

  			function LegendSelect($image)
  			{

  						if (!isset($this->conf['Legend Settings']['nolegend']) || !$this->form['nolegend'] == '1')
  						{
  									if ($this->conf['Legend Settings']['legend_type'] == 'legend_square')
  									{
  												$image = $this->legend_square($image, $this->conf['Legend Settings']);
  												return $image;
  									} elseif ($this->conf['Legend Settings']['legend_type'] == 'single_row_legend_square')
  									{
  												$image = $this->single_row_legend_square($image, $this->conf['Legend Settings']);
  												return $image;
  									} elseif ($this->conf['Legend Settings']['legend_type'] == 'legend_vertical_compact')
  									{
  												$image = $this->legend_vertical_compact($image, $this->conf['Legend Settings']);
  												return $image;
  									} elseif ($this->conf['Legend Settings']['legend_type'] == 'legend_horizontal_compact')
  									{
  												$image = $this->legend_horizontal_compact($image, $this->conf['Legend Settings']);

  												return $image;
  									}
  						}


  			}


  			function legend_square($image, $legendcfg)
  			{
  						$size = imagesx($image);
  						$fontpath = (isset($legendcfg['TTF_path'])) ? $legendcfg['TTF_path'] : TTFPATH;
  						$font = (isset($legendcfg['TTF_font'])) ? $legendcfg['TTF_font'] : 'Arialnb.ttf';
  						$fontsize = (isset($legendcfg['TTF_size'])) ? $legendcfg['TTF_size'] : '10';
  						$fontcolor = (isset($legendcfg['font_color'])) ? $legendcfg['font_color'] : 'FFFFFF';

  						$legendbg_h = (isset($legendcfg['legendbg_h'])) ? $legendcfg['legendbg_h'] : '55';
  						$legendy = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';
  						$legendcolorsa = (isset($legendcfg['legend_colorsa'])) ? $legendcfg['legend_colorsa'] : '';
  						$legendlabelsa = (isset($legendcfg['legend_labelsa'])) ? $legendcfg['legend_labelsa'] : '';

  						$legendcolorsb = (isset($legendcfg['legend_colorsb'])) ? $legendcfg['legend_colorsb'] : '';
  						$legendlabelsb = (isset($legendcfg['legend_labelsb'])) ? $legendcfg['legend_labelsb'] : '';

  						$black = imagecolorallocate($image, 0, 0, 0);


  						/**
  						 * lets get the labels size
  						 * 
  						 */
  						$atotal = count($legendlabelsa);
  						$afontw = 0;
  						foreach ($legendlabelsa as $label)
  						{
  									list($w, $h) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
  									if ($w > $afontw)
  									{
  												$afontw = $w;
  									}
  						}
  						$ablocksw = ($atotal * 10) * 3;
  						$afontwt = $atotal * $afontw;
  						$aimgw = $afontwt + $ablocksw;

  						if ($this->debug)
  									echo "top legend width $aimgw $afontw\n";


  						$btotal = count($legendlabelsb);
  						$bfontw = 0;

  						foreach ($legendlabelsb as $label)
  						{
  									list($w, $h) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
  									if ($w > $bfontw)
  									{
  												$bfontw = $w;
  									}
  						}
  						$blocksw = ($btotal * 10) * 3;
  						$bfontwt = $btotal * $bfontw;
  						$imgw = $bfontwt + $blocksw;

  						if ($this->debug)
  									echo "bottom legend width $imgw $bfontw\n";


  						$fontw = max($afontw, $bfontw);
  						$legendw = max($aimgw, $imgw);

  						if ($this->debug)
  									print "and the winner is $legendw\n";


  						$legendimage = imagecreatetruecolor($legendw, 45);

  						$black = imagecolorallocate($legendimage, 0, 0, 0);


  						$fill = imagecolorallocate($legendimage, 25, 25, 25);

  						imagefilledrectangle($legendimage, 0, 0, imagesx($legendimage), imagesy($legendimage), $fill);

  						imagecolortransparent($legendimage, $fill);

  						list($r, $g, $b) = $this->tools->getRGB($fontcolor);
  						$fontc = imagecolorallocate($legendimage, $r, $g, $b);
  						error_reporting(0);

  						/** BEGIN LEGEND TOP **/


  						$x = $cc = $y = 0;
                        $x = '1';
  						$ffontw = array();
  						foreach ($legendlabelsa as $label)
  						{


  									$ffontw[$y] = $fontw + 10;

  									list($r, $g, $bl) = $this->tools->getRGB($legendcolorsa[$x]);
  									$fillcolor = imagecolorallocate($legendimage, $r, $g, $bl);

  									

  												
  												
  												if (!empty($label))
  												{
  															imagefilledrectangle($legendimage, $cc + 10, 5, $cc + 10 + 12, 16, $black);
  															imagefilledrectangle($legendimage, $cc + 11, 6, $cc + 11 + 10, 15, $fillcolor);

  															imagettftext($legendimage, $fontsize, 0, $cc + 11 + 12 + 5, 16, $black, $fontpath . $font, $label);
  															imagettftext($legendimage, $fontsize, 0, $cc + 10 + 11 + 5, 15, $fontc, $fontpath . $font, $label);
  												}
  									

  									$y++;
  									$x++;
                                    $t = $y - 1;
                                    $cc = $cc + $ffontw[$t] + 12;
  						}


  						/** BEGIN LEGEND BOTTOM **/


  						$x = $cc = $y = 0;
                        $x = '1';
  						$ffontw = array();
  						foreach ($legendlabelsb as $label)
  						{


  									$ffontw[$y] = $fontw + 10;
                                    
                                    

  									list($r, $g, $bl) = $this->tools->getRGB($legendcolorsb[$x]);
  									$fillcolor = imagecolorallocate($legendimage, $r, $g, $bl);

  													
  												if (!empty($label))
  												{
  															imagefilledrectangle($legendimage, $cc + 10, 20, $cc + 10 + 12, 31, $black);
  															imagefilledrectangle($legendimage, $cc + 11, 21, $cc + 11 + 10, 30, $fillcolor);

  															imagettftext($legendimage, $fontsize, 0, $cc + 11 + 12 + 5, 31, $black, $fontpath . $font, $label);
  															imagettftext($legendimage, $fontsize, 0, $cc + 10 + 11 + 5, 30, $fontc, $fontpath . $font, $label);
  												}
  									

  									$y++;
  									$x++;
                                    $t = $y - 1;
                                    $cc = $cc + $ffontw[$t] + 12;
  						}


  						$mapc = imagesx($image) * .5;
  						$legc = imagesx($legendimage) * .5;

  						$place = round($mapc) - round($legc) + 20;
  						if ($this->debug)
  									print "place $place mapc $mapc legc $legc\n";

  						$black = imagecolorallocate($image, 0, 0, 0);
  						$image = $this->legend_bg2($image, $black, $legendbg_h);
  						imagecopymerge($image, $legendimage, $place, $legendy, 0, 0, imagesx($legendimage), imagesy($legendimage), 100);

  						return $image;


  			}
  			/**
  			 * 
  			 * Single row legend square
  			 * 
  			 * **/

  			function single_row_legend_square($image, $legendcfg)
  			{
  						$size = imagesx($image);


  						$fontpath = (isset($legendcfg['TTF_path'])) ? $legendcfg['TTF_path'] : TTFPATH;
  						$font = (isset($legendcfg['TTF_font'])) ? $legendcfg['TTF_font'] : 'Arialnb.ttf';
  						$fontsize = (isset($legendcfg['TTF_size'])) ? $legendcfg['TTF_size'] : '10';
  						$fontcolor = (isset($legendcfg['font_color'])) ? $legendcfg['font_color'] : 'FFFFFF';

  						$legendbg_h = (isset($legendcfg['legendbg_h'])) ? $legendcfg['legendbg_h'] : '55';
  						$legendy = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';
  						$labels = (isset($legendcfg['legend_labels'])) ? $legendcfg['legend_labels'] : '';

  						$black = imagecolorallocate($image, 0, 0, 0);

  						if ($this->debug)
  						{
  									print "Fontinfo $fontpath / $font / $fontsize";
  						}
                        
                        $legendlabels = array();

  						$colors = $this->conf['Colors'];
  						$i = 0;
  						foreach ($colors as $key => $color)
  						{
  									$legendcolors[$i] = $color;
  									$i++;
  						}
                        
                        if (is_array($labels))
                        {
                            $legendlabels = $labels;
                        }
                        elseif (preg_match("/,/" , $labels)){
                            $legendlabels = explode(",",$labels);
                        }
                        else
                        {
                            array_push($legendlabels,$labels);
                        }
                        

  						/**
  						 * lets get the labels size
  						 * 
  						 */
                         if ($this->debug) echo "Count:: " . count($legendlabels);
  						$atotal = count($legendlabels);
  						$afontw = 0;
  						foreach ($legendlabels as $label)
  						{
  									list($w, $h) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
  									if ($w > $afontw)
  									{
  												$afontw = $w;
  									}
  						}
  						$ablocksw = ($atotal * 10) * 3;
  						$afontwt = $atotal * $afontw;
  						$legendw = $afontwt + $ablocksw;

  						if ($this->debug)
  									echo "top legend width $legendw $afontw\n";


  						$legendimage = imagecreatetruecolor($legendw, 45);

  						$black = imagecolorallocate($legendimage, 0, 0, 0);


  						$fill = imagecolorallocate($legendimage, 25, 25, 25);

  						imagefilledrectangle($legendimage, 0, 0, imagesx($legendimage), imagesy($legendimage), $fill);

  						imagecolortransparent($legendimage, $fill);

  						list($r, $g, $b) = $this->tools->getRGB($fontcolor);
  						$fontc = imagecolorallocate($legendimage, $r, $g, $b);
  						//error_reporting(0);

  						/** BEGIN LEGEND TOP **/


  						$x = $cc = $y = 0;
  						$ffontw = array();
  						foreach ($legendlabels as $label)
  						{


  									$ffontw[$y] = $afontw + 10;

  									list($r, $g, $bl) = $this->tools->getRGB($legendcolors[$x]);
  									$fillcolor = imagecolorallocate($legendimage, $r, $g, $bl);

  									if ($x == 0)
  									{


  												imagefilledrectangle($legendimage, $x * $ffontw[$y] + 10, 5, $x * $ffontw[$y] + 10 + 12, 16, $black);
  												imagefilledrectangle($legendimage, $x * $ffontw[$y] + 11, 6, $x * $ffontw[$y] + 11 + 10, 15, $fillcolor);

  												imagettftext($legendimage, $fontsize, 0, $x * $ffontw[$y] + 30, 16, $black, $fontpath . $font, $label);
  												imagettftext($legendimage, $fontsize, 0, $x * $ffontw[$y] + 29, 15, $fontc, $fontpath . $font, $label);
  									} else
  									{
  												$t = $y - 1;
  												$cc = $cc + $ffontw[$t] + 12;

  												imagefilledrectangle($legendimage, $cc + 10, 5, $cc + 10 + 12, 16, $black);
  												imagefilledrectangle($legendimage, $cc + 11, 6, $cc + 11 + 10, 15, $fillcolor);

  												imagettftext($legendimage, $fontsize, 0, $cc + 11 + 12 + 5, 16, $black, $fontpath . $font, $label);
  												imagettftext($legendimage, $fontsize, 0, $cc + 10 + 11 + 5, 15, $fontc, $fontpath . $font, $label);
  									}

  									$y++;
  									$x++;
  						}


  						$mapc = imagesx($image) * .5;
  						$legc = imagesx($legendimage) * .5;

  						$place = round($mapc) - round($legc) + 20;
  						if ($this->debug)
  									print "place $place mapc $mapc legc $legc\n";

  						$black = imagecolorallocate($image, 0, 0, 0);
  						$image = $this->legend_bg2($image, $black, $legendbg_h);
  						imagecopymerge($image, $legendimage, $place, $legendy, 0, 0, imagesx($legendimage), imagesy($legendimage), 100);

  						return $image;


  			}


  			/**
  			 * Legend::legend_vertical_compact()
  			 * 
  			 * @return
  			 */
  			function legend_vertical_compact($image, $legendcfg)
  			{

  						$size = imagesx($image);
  						$fontpath = (isset($legendcfg['legend_TTF_path'])) ? $legendcfg['legend_TTF_path'] : TTFPATH;
  						$font = (isset($legendcfg[$size . 'legend_TTF'])) ? $legendcfg[$size . 'legend_TTF'] : 'arialbd.ttf';
  						$fontsize = (isset($legendcfg[$size . 'legend_TTF_size'])) ? $legendcfg[$size . 'legend_TTF_size'] : '10';
  						$fontcolor = (isset($legendcfg['legend_font_color'])) ? $legendcfg['legend_font_color'] : 'FFFFFF';

  						$labels = (isset($legendcfg['legend_labels'])) ? $legendcfg['legend_labels'] : '';
  						$legendwhat = (isset($legendcfg['legend_what'])) ? $legendcfg['legend_what'] : 'inches';

  						$blockht = (isset($legendcfg['block_ht'])) ? $legendcfg['block_ht'] : '3';
  						$black = imagecolorallocate($image, 0, 0, 0);
  						$white = imagecolorallocate($image, 255, 255, 255);
                        
                        $legendbg_op = (isset($legendcfg['background'])) ? $legendcfg['background'] : '55';
                        
                        if (is_array($labels))
  						{
  									$legendlabels = $labels;
  						} else
  						{
  									$legendlabels = explode(",", $labels);
  						}


  						$colors = $this->conf['Colors'];
                        $i =0;
  						foreach ($colors as $key => $color)
  						{
  									$legendcfg['legend_colors'][$i] = $color;
                                    $i++;
  						}

  						$x = $y = $cc = 0;
                        
                        
                        
                        
                        $legendcolors = $legendcfg['legend_colors'];

  						$legendimageh = (count($legendcolors) * $blockht) + 20;
  						$legendimagew = (isset($legendcfg['legend_width'])) ? $legendcfg['legend_width'] : '40';
  						if ($this->debug)
  									echo "Legendx $legendimagew legendy $legendimageh\n";
  						$legendimage = imagecreatetruecolor($legendimagew, $legendimageh);

  						$black = imagecolorallocate($legendimage, 0, 0, 0);
  						$white = imagecolorallocate($legendimage, 255, 255, 255);

  						$fill = imagecolorallocate($legendimage, 25, 25, 25);

  						imagefilledrectangle($legendimage, 0, 0, $legendimagew, $legendimageh, $fill);

  						imagecolortransparent($legendimage, $fill);


  				

  						foreach ($legendlabels as $label)
  						{

  									list($w[$y], $h[$y]) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
                                    
                                   
                                    

  									$lh = $legendimageh - ($blockht * $y) -5;
  									
  												if ($this->debug)
  															print "lh $lh\n";
  												list($r, $g, $bl) = $this->tools->getRGB($legendcolors[$x]);
  												$fillcolor = imagecolorallocate($image, $r, $g, $bl);


  												imagefilledrectangle($legendimage, 5, $lh, 12, $lh - 8, $fillcolor);
                                                imagerectangle($legendimage, 4, $lh+1, 13, $lh - 9, $black);



  												imagettftext($legendimage, $fontsize, 0, 15, $lh + 2, $white, $fontpath . $font, $label);
  									

  									$y++;
  									$x++;
  						}
  						imagettftext($legendimage, $fontsize, 0, 5, 12, $white, $fontpath . $font, $legendwhat);

  						$place = imagesy($image) - $legendimageh - 40;
  						if ($this->debug)
  									echo "place $place\n";

  						if (strtoupper($legendcfg['legend_side']) == 'RIGHT')
  						{

  									$side = imagesx($image) - $legendimagew - 5;

  						} else
  						{
  									$side = 3;
  						}
  						$black = imagecolorallocate($image, 0, 0, 0);
  						$bg = imagecreatetruecolor($legendimagew, $legendimageh);

  						imagefilledrectangle($bg, 0, 0, $legendimagew, $legendimageh, $black);
  						imagecopymerge($image, $bg, $side, $place, 0, 0, $legendimagew, $legendimageh, $legendbg_op);
  						


  						imagecopymerge($image, $legendimage, $side, $place, 0, 0, $legendimagew, $legendimageh, 100);

  						return $image;


  			}


  			function legend_horizontal_compact($image, $legendcfg)
  			{


  						$contour = new Contour($this->debug,$this->form, $this->conf,$this->tools);
  						$legendcfg['legend_colors'] = $contour->set_colors($this->conf['Colors']);

  						$steps = (isset($this->conf['Color Settings']['Contour_steps'])) ? $this->conf['Color Settings']['Contour_steps'] : '10';

  						$size = imagesx($image);
  						$fontpath = (isset($legendcfg['legend_TTF_path'])) ? $legendcfg['legend_TTF_path'] : TTFPATH;
  						$font = (isset($legendcfg['legend_TTF'])) ? $legendcfg['legend_TTF'] : 'arialbd.ttf';
  						$fontsize = (isset($legendcfg['legend_TTF_size'])) ? $legendcfg['legend_TTF_size'] : '10';
  						$fontcolor = (isset($legendcfg['legend_font_color'])) ? $legendcfg['legend_font_color'] : 'FFFFFF';
  						$legendcolors = (isset($legendcfg['legend_colors'])) ? $legendcfg['legend_colors'] : '';
  						$legendlabels = (isset($legendcfg['legend_labels'])) ? $legendcfg['legend_labels'] : '';
  						$legendwhat = (isset($legendcfg['legend_what'])) ? $legendcfg['legend_what'] : '';
  						$legendwidth = (isset($legendcfg['legend_width'])) ? $legendcfg['legend_width'] : '520';
  						$legendbarheight = (isset($legendcfg['legend_bar_height'])) ? $legendcfg['legend_bar_height'] : '8';
  						$legendy = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';

  						$legendbg_h = (isset($legendcfg['legendbg_h'])) ? $legendcfg['legendbg_h'] : '55';

  						$labels_x = (isset($legendcfg['labels_x'])) ? $legendcfg['labels_x'] : '0';


  						$x = $y = $cc = 0;

  						list($w, $h) = $this->tools->_text_width_height($legendwhat, $fontpath, $font, $fontsize, '1');
  						$legendimageh = $h + $legendbarheight + 20;
  						$legendimagew = imagesx($image);
  						if ($this->debug)
  									echo "Legendx $legendimagew legendy $legendimageh text $w\n";
  						$legendimage = imagecreatetruecolor($legendimagew, $legendimageh);

  						$black = imagecolorallocate($legendimage, 0, 0, 0);
  						$white = imagecolorallocate($legendimage, 255, 255, 255);

  						$fill = imagecolorallocate($legendimage, 25, 25, 25);

  						imagefilledrectangle($legendimage, 0, 0, $legendimagew, $legendimageh, $fill);

  						imagecolortransparent($legendimage, $fill);


  						$boxw = round($legendwidth / count($legendcolors));

  						$legendcenter = floor($legendwidth * .5);
  						$imagecenter = floor(imagesx($image) * .5);

  						$legendstart = $imagecenter - $legendcenter;

  						if ($this->debug)
  									print "$boxw ... \n";

  						$labels = explode(",", $legendlabels);
  						$i = 0;

  						foreach ($legendcolors as $key => $color)
  						{


  									$lw = $legendstart + ($boxw * $y);


  									list($r, $g, $bl) = $this->tools->getRGB($color);
  									$fillcolor = imagecolorallocate($image, $r, $g, $bl);


  									imagefilledrectangle($legendimage, $lw, 3, $lw + $boxw, 3 + $legendbarheight, $fillcolor);

  									if ($legendlabels)
  									{
  												if ($key % $steps == '0')
  												{
  															imagettftext($legendimage, $fontsize, 0, $lw + $labels_x, $legendbarheight + 18, $black, $fontpath . $font, $labels[$i]);
  															imagettftext($legendimage, $fontsize, 0, $lw + $labels_x, $legendbarheight + 17, $white, $fontpath . $font, $labels[$i]);
  															$i++;
  												}
  									}

  									$y++;
  									$x++;
  						}

  						imagerectangle($legendimage, $legendstart, 3, $lw + $boxw, 3 + $legendbarheight, $black);

  						imagettftext($legendimage, $fontsize, 0, $lw + $boxw + 2, 13, $black, $fontpath . $font, $legendwhat);
  						imagettftext($legendimage, $fontsize, 0, $lw + $boxw + 3, 12, $white, $fontpath . $font, $legendwhat);

  						$mapc = imagesx($image) * .5;
  						$legc = imagesx($legendimage) * .5;

  						$place = round($mapc) - round($legc);
  						if ($this->debug)
  									print "place $place mapc $mapc legc $legc\n";

  						$black = imagecolorallocate($image, 0, 0, 0);
  						$image = $this->legend_bg2($image, $black, $legendbg_h);
  						imagecopymerge($image, $legendimage, $place, $legendy, 0, 0, $legendimagew, $legendimageh, 100);

  						return $image;


  			}


  			function legend_bg($image, $black)
  			{

  						$width = imagesx($image);

  						$bg = imagecreatetruecolor($width, '45');

  						imagefilledrectangle($bg, 0, 0, $width, 45, $black);
  						imagecopymerge($image, $bg, 0, 28, 0, 0, $width, 45, 45);

  						return $image;

  			}

  			function legend_bg2($image, $black, $y)
  			{

  						$width = imagesx($image);

  						$bg = imagecreatetruecolor($width, $y);

  						imagefilledrectangle($bg, 0, 0, $width, $y, $black);
  						imagecopymerge($image, $bg, 0, 30, 0, 0, $width, $y, 45);

  						return $image;

  			}


  } //end class


?>