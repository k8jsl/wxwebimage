<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->3/10/2013 11:54:05<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Plotter.inc.php
   * @version 2
   * @revision .04
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */
  class Plotter
  {

  			var $distance = '';
            var $version = '2.05';


  			function __construct($debug, $form, $conf, $tools, $database, $mysql)
  			{

  						$this->tools = $tools;
  						$this->conf = $conf;
  						$this->debug = $debug;
  						$this->form = $form;
  						$this->database = $database;
  						$this->mysql = $mysql;
                        if ($debug) echo "Plotter:: " . $this->version . "\n";

  			}


  			function do_cities($image, $mapplot)
  			{

  						if (empty($this->conf['Cities']))
  						{
  									return $image;

  						}
  						$px = $py = '';

  						$this->distance = (!empty($this->conf['City Settings']['distance'])) ? $this->conf['City Settings']['distance'] : "0";
  						$fontcolor = (!empty($this->conf['City Settings']['TTF_font_color'])) ? $this->conf['City Settings']['TTF_font_color'] : 'FFFFFF';
  						$dscolor = (!empty($this->conf['City Settings']['TTF_ds_color'])) ? $this->conf['City Settings']['TTF_ds_color'] : '000000';
  						$fontsize = (!empty($this->conf['City Settings']['TTF_font_pt'])) ? $this->conf['City Settings']['TTF_font_pt'] : '10';
  						$ttffont = (!empty($this->conf['City Settings']['TTF_font'])) ? $this->conf['City Settings']['TTF_font'] : 'arial.ttf';
  						$marker = (!empty($this->conf['City Settings']['marker'])) ? $this->conf['City Settings']['marker'] : '';
  						list($markertype, $mw, $mh, $mcolor) = explode("|", $marker);

  						$gicaos = array();

  						foreach ($this->conf['Cities'] as $key => $value)
  						{
  									list($city, $state) = explode(",", $value);

  									
  									$global = $this->database->get_global($city,$state);

  									$lat = $global['lat'];
                                    $lon = $global['lon'];
                                    
  									list($px, $py) = $mapplot->LL_to_XY($lon, $lat);


  									array_push($gicaos, "$city,$state,$px,$py");
  						}


  						$plots = self::_distance_loop($gicaos);


  						foreach ($plots as $plot)
  						{

  									list($place, $state, $px, $py) = explode(",", $plot);


  									$bounding = self::_bounding_box($image, $px, $py);

  									if ($bounding == '1')
  									{

  												if (strtolower($markertype) == 'square')
  												{
  															$image = self::_square($image, $px, $py);
  												} elseif (strtolower($markertype) == 'ellipse')
  												{
  															$image = self::_ellipse($image, $px, $py);
  												}
  												$image = self::_square($image, $px, $py);

  												$image = self::plotcity($image, $px, $py, ucwords(strtolower($place)));


  									}


  						}


  						return $image;
  			}

  			function _distance_loop($gicaos)
  			{
  						$plotted = $ploticao = array();
  						$px = $py = '';
  						foreach ($gicaos as $good)
  						{
  									$xx2 = $yy2 = '';
  									list($place, $state, $xx, $yy) = preg_split("/,/", $good);
  									$plot = 1;


  									foreach ($plotted as $point)
  									{

  												list($px, $py) = $point;

  												$d = self::_do_distance($xx, $yy, $px, $py);

  												if ($d < $this->distance)
  												{
  															$plot = 0;
  															break;
  												}
  									}
  									if ($plot)
  									{
  												array_push($plotted, array($xx, $yy));
  												array_push($ploticao, "$place,$state,$xx,$yy");
  									}
  						}

  						return $ploticao;
  			}

  			function _do_distance($xx, $yy, $px, $py)
  			{


  						if ($px > $xx && $py > $yy)
  						{
  									$dis = sqrt(pow(($px - $xx), 2) + pow(($py - $yy), 2));
  						} elseif ($px > $xx && $py < $yy)
  						{
  									$dis = sqrt(pow(($px - $xx), 2) + pow(($yy - $py), 2));
  						} elseif ($px < $xx && $py > $yy)
  						{
  									$dis = sqrt(pow(($xx - $px), 2) + pow(($py - $yy), 2));
  						} elseif ($px < $xx && $py < $yy)
  						{
  									$dis = sqrt(pow(($xx - $px), 2) + pow(($yy - $py), 2));
  						}


  						return floor($dis);
  			}


  			function _bounding_box($image, $px, $py)
  			{
  						$iw = imagesx($image);
  						$ih = imagesy($image);

  						// 45 from top, 15 sides, 25 bottom

  						if ($py > '95' && $py < $ih - '25' && $px > '18' && $px < $iw - '19')
  						{
  									return 1;
  						} else
  						{
  									return 0;
  						}


  			}
  			function plotcity($image, $px, $py, $place)
  			{
  						$size = imagesx($image);
  						$chest['ttfpath'] = (!empty($this->conf['City Settings']['TTF_path'])) ? $this->conf['City Settings']['TTF_path'] : TTFPATH;
  						$chest['ttffont'] = (!empty($this->conf['City Settings']['TTF_font'])) ? $this->conf['City Settings']['TTF_font'] : 'arialbd.ttf';
  						$chest['ttfpt'] = (!empty($this->conf[$size . 'City Settings']['TTF_font_pt'])) ? $this->conf[$size . 'City Settings']['TTF_font_pt'] :
  									'12';
  						$chest['ttfangle'] = (!empty($this->conf['City Settings']['TTF_font_angle'])) ? $this->conf['City Settings']['TTF_font_angle'] :
  									'0';
  						$chest['ttfcolor'] = (!empty($this->conf['City Settings']['font_color'])) ? $this->conf['City Settings']['font_color'] : 'FFFFFF';
  						$chest['dscolor'] = (!empty($this->conf['City Settings']['ds_color'])) ? $this->conf['City Settings']['ds_color'] : '000000';
  						$chest['dsxoff'] = (!empty($this->conf['City Settings']['ds_xoffset'])) ? $this->conf['City Settings']['ds_xoffset'] : '1';
  						$chest['dsyoff'] = (!empty($this->conf['City Settings']['ds_yoffset'])) ? $this->conf['City Settings']['ds_yoffset'] : '1';
  						$chest['dsoutline'] = (!empty($this->conf['City Settings']['ds_outline'])) ? $this->conf['City Settings']['ds_outline'] : '0';
  						$chest['xoff'] = (!empty($this->conf['City Settings']['xoff'])) ? $this->conf['City Settings']['xoff'] : '5';
  						$chest['yoff'] = (!empty($this->conf['City Settings']['yoff'])) ? $this->conf['City Settings']['yoff'] : '0';

  						$image = $this->tools->do_text($image, $place, $px, $py, $chest);

  						return $image;

  			}

  			function _square($image, $x, $y)
  			{

  						list($type, $w, $h, $color) = explode("|", $this->conf['City Settings']['marker']);

  						$iconcolor = $this->tools->color($image,$color);
  						
  						$black = $this->tools->color($image, '000000');


  						imagefilledrectangle($image, $x - ($w * .5), $y - ($h * .5), $x + ($w * .5), $y + ($h * .5), $iconcolor);
  						imagerectangle($image, $x - ($w * .5), $y - ($h * .5), $x + ($w * .5), $y + ($h * .5), $black);

  						return $image;


  			}

  			function _ellipse($image, $x, $y)
  			{

  						list($type, $w, $h, $color) = explode("|", $this->conf['City Settings']['marker']);

  						$iconcolor = $this->tools->color($image,$color);
  						
  						$black = $this->tools->color($image, '000000');

  						imagefilledellipse($image, $px, $py, $w, $h, $iconcolor);
  						imageellipse($image, $px, $py, $w, $h, $black);
  						return $image;
  			}
  }

?>