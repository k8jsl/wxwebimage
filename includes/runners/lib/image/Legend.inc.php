<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->3/14/2013 21:13:03<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Legend.inc.php
   * 
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */

  class Legend
  {
  			var $VERSION = "WxWeb Image Legend.php 3.57<br />";

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
  						$legend_y = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';
  						$legendcolorsa = (isset($legendcfg['legend_colorsa'])) ? $legendcfg['legend_colorsa'] : '';
  						$legendlabelsa = (isset($legendcfg['legend_labelsa'])) ? $legendcfg['legend_labelsa'] : '';

  						$legendcolorsb = (isset($legendcfg['legend_colorsb'])) ? $legendcfg['legend_colorsb'] : '';
  						$legendlabelsb = (isset($legendcfg['legend_labelsb'])) ? $legendcfg['legend_labelsb'] : '';

  						$black = $this->tools->color($image,'000000');


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


                        $legendstart = round(imagesx($image) * .5) - round($legendw * .5);

  						$black = imagecolorallocate($image, 0, 0, 0);
  						$image = $this->legend_bg2($image, $black, $legendbg_h);

  						$fontc = $this->tools->color($image,$fontcolor);
  						
  						error_reporting(0);

  						/** BEGIN LEGEND TOP **/


  						$x = $cc = $y = 0;
                        $x = '1';
  						$ffontw = array();
  						foreach ($legendlabelsa as $label)
  						{


  									$ffontw[$y] = $fontw + 10;

  									$fillcolor = $this->tools->color($image,$legendcolorsa[$x]);
  									

  									

  												
  												
  												if (!empty($label))
  												{
  															imagefilledrectangle($image, $cc + 10 + $legendstart, 5 + $legend_y, $cc + $legendstart + 10 + 12, 16 + $legend_y, $black);
  															imagefilledrectangle($image, $cc + 11 + $legendstart, 6 + $legend_y, $cc + $legendstart + 11 + 10, 15 + $legend_y, $fillcolor);

  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 11 + 12 + 5, 16 + $legend_y, $black, $fontpath . $font, $label);
  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 10 + 11 + 5, 15 + $legend_y, $fontc, $fontpath . $font, $label);
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
                                    
                                    

  									$fillcolor = $this->tools->color($image,$legendcolorsb[$x]);

  													
  												if (!empty($label))
  												{
  															imagefilledrectangle($image, $cc + 10 + $legendstart, 20 + $legend_y, $cc + $legendstart + 10 + 12, 31 + $legend_y, $black);
  															imagefilledrectangle($image, $cc + 11 + $legendstart, 21 + $legend_y, $cc + $legendstart + 11 + 10, 30 + $legend_y, $fillcolor);

  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 11 + 12 + 5, 31 + $legend_y, $black, $fontpath . $font, $label);
  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 10 + 11 + 5, 30 + $legend_y, $fontc, $fontpath . $font, $label);
  												}
  									

  									$y++;
  									$x++;
                                    $t = $y - 1;
                                    $cc = $cc + $ffontw[$t] + 12;
  						}


  						
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
  						$legend_y = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';
  						$labels = (isset($legendcfg['legend_labels'])) ? $legendcfg['legend_labels'] : '';

  						$black = imagecolorallocate($image, 0, 0, 0);
  						$image = $this->legend_bg2($image, $black, $legendbg_h);

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
                         if ($this->debug) echo "\nCount:: " . count($legendlabels)."\n";
  						$atotal = count($legendlabels);
  						$a = $legendw = 0;
  						foreach ($legendlabels as $label)
  						{
								    list($w, $h) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
                                    $labelw[$a] = $w;
                                    if($this->debug) print "label $label $w\n";
                                    $legendw += $labelw[$a] + 10;
                                    $a++;
  						}
  						$ablocksw = ($atotal * 10);
  						$legendw = $legendw + $ablocksw;
                        $legendcenter = round($legendw * .5);
                        $legendstart = round(imagesx($image) * .5) - $legendcenter;

  						if ($this->debug)
  									echo "\ntop legend width $legendw Start: $legendstart Center: $legendcenter\n";


 		

  						

  						$fontc = $this->tools->color($image,$fontcolor);
  						

  						/** BEGIN LEGEND TOP **/


  						$x = $cc = $y = 0;
  						$ffontw = array();
  						foreach ($legendlabels as $label)
  						{


  										$ffontw[$y] = $labelw[$y] + 10;

  									$fillcolor = $this->tools->color($image,$legendcolors[$x]);
  									

  									

  												
  												
  												if (!empty($label))
  												{
  															imagefilledrectangle($image, $cc + 10 + $legendstart, 5 + $legend_y, $cc + $legendstart + 10 + 12, 16 + $legend_y, $black);
  															imagefilledrectangle($image, $cc + 11 + $legendstart, 6 + $legend_y, $cc + $legendstart + 11 + 10, 15 + $legend_y, $fillcolor);

  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 11 + 12 + 5, 16 + $legend_y, $black, $fontpath . $font, $label);
  															imagettftext($image, $fontsize, 0, $cc + $legendstart + 10 + 11 + 5, 15 + $legend_y, $fontc, $fontpath . $font, $label);
  												}
  									

  									$y++;
  									$x++;
                                    $t = $y - 1;
                                    $cc = $cc + $ffontw[$t] + 12;
  						}


  						

  						
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

  						$black = $this->tools->color($legendimage, '000000');
  						$white = $this->tools->color($legendimage, 'ffffff');

  						$fill = $this->tools->color($legendimage, 'FFFFEA');

  						imagefilledrectangle($legendimage, 0, 0, $legendimagew, $legendimageh, $fill);

  						imagecolortransparent($legendimage, $fill);


  				

  						foreach ($legendlabels as $label)
  						{

  									list($w[$y], $h[$y]) = $this->tools->_text_width_height($label, $fontpath, $font, $fontsize, '0');
                                    
                                   
                                    

  									$lh = $legendimageh - ($blockht * $y) -5;
  									
  												if ($this->debug)
  															print "lh $lh\n";
  												$fillcolor = $this->tools->color($legendimage,$legendcolors[$x]);
  												


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
  						$black = $this->tools->color($image, '000000');
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
  						$legend_y = (isset($legendcfg['legend_y'])) ? $legendcfg['legend_y'] : '40';

  						$legendbg_h = (isset($legendcfg['legendbg_h'])) ? $legendcfg['legendbg_h'] : '55';

  						$labels_x = (isset($legendcfg['labels_x'])) ? $legendcfg['labels_x'] : '0';


  						$x = $y = $cc = 0;

  						list($w, $h) = $this->tools->_text_width_height($legendwhat, $fontpath, $font, $fontsize, '1');
  						$legendimageh = $h + $legendbarheight + 20;
  						$legendimagew = imagesx($image);
  						if ($this->debug)
  									echo "Legendx $legendimagew legendy $legendimageh text $w\n";
  						
  						$black = $this->tools->color($image, '000000');
  						$white = $this->tools->color($image, 'FFFFFF');

  						$image = $this->legend_bg2($image, $black, $legendbg_h);


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


  									$fillcolor = $this->tools->color($image,$color);
  									

  									imagefilledrectangle($image, $lw, $legend_y, $lw + $boxw, 3 + $legendbarheight + $legend_y, $fillcolor);

  									if ($legendlabels)
  									{
  												if ($key % $steps == '0')
  												{
  															imagettftext($image, $fontsize, 0, $lw + $labels_x, $legend_y + $legendbarheight + 18, $black, $fontpath . $font, $labels[$i]);
  															imagettftext($image, $fontsize, 0, $lw + $labels_x, $legend_y + $legendbarheight + 17, $white, $fontpath . $font, $labels[$i]);
  															$i++;
  												}
  									}

  									$y++;
  									$x++;
  						}

  						imagerectangle($image, $legendstart, $legend_y, $lw + $boxw, $legend_y + 3 + $legendbarheight, $black);

  						imagettftext($image, $fontsize, 0, $lw + $boxw + 2, $legend_y + 13, $black, $fontpath . $font, $legendwhat);
  						imagettftext($image, $fontsize, 0, $lw + $boxw + 3, $legend_y + 12, $white, $fontpath . $font, $legendwhat);

  					
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