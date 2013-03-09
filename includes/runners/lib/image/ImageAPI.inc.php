<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->1/8/2013 18:30:22<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Image.inc.php
   * @version 3
   * @revision .06
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   */


  require (LIBPATH . "/image/Tools.inc.php");
  require (LIBPATH . "/image/Legend.inc.php");
  require (LIBPATH . "/image/maps/Projection.inc.php");
  require (LIBPATH . "/image/Contour.inc.php");
  require (LIBPATH . "/image/Plotter.inc.php");


  define("IMAGEAPIVERSION", "ImageAPI 1.05 11/19/2012 13:46:51");
  class ImageAPI extends WxWebAPI
  {
  			var $map;
  			var $blankmaps;
  			var $mapplot;
  			var $gd_ext;
  			var $wwconf;


  			function __construct($runconf, $form, $database, $fetch, $conf, $debug, $mysql, $smarty)
  			{


  						$this->fetch = $fetch;

  						$this->runconf = $runconf;
  						$this->form = $form;
  						$this->debug = $debug;
  						$this->conf = $conf;
  						$this->mysql = $mysql;
  						$this->smarty = $smarty;
  						$this->database = $database;


  						if ($this->debug)
  						{
  									echo IMAGEAPIVERSION;
  						}

  						$this->smarty->assign('imageapi_version', IMAGEAPIVERSION);

  						ImageAPI::_main($this->debug, $this->form, $this->conf, $this->mysql, $this->smarty);


  			}

  			function _main($debug, $form, $conf, $mysql, $smarty)
  			{



  						define("TTFPATH", $this->conf['Image Settings']['TTF_path']."/");

  						define("WATERMARKPATH", $this->conf['Image Settings']['blankmaps']);


  						$this->tools = new Tools($this->debug, $this->form, $this->conf);

  						$this->projection = new Projection($this->debug, $this->form, $this->conf, $this->tools);

  						$this->contour = new Contour($this->debug, $this->form, $this->conf, $this->tools);
  						$this->legend = new Legend($this->debug, $this->form, $this->conf, $this->tools);
  						$this->plotter = new Plotter($this->debug, $this->form, $this->conf, $this->tools, $this->database, $this->mysql);

  						$this->image = ImageAPI::_load_map();


  			}


  			function _load_map()
  			{

  						$this->map = (!empty($this->form['map'])) ? $this->form['map'] : $this->conf['Image Settings']['map'];


  						if (!preg_match('/\w+/', $this->map))
  						{
  									echo "WxWeb Error: basemap not set<br />\n";
  									return 0;
  						}
  						$this->blankmaps = (isset($this->conf['Image Settings']['blankmaps'])) ? $this->conf['Image Settings']['blankmaps'] : '';
  						if (!preg_match('/\w+/', $this->blankmaps))
  						{
  									echo "WxWeb Error: blankmap path not set<br />\n";
  									return 0;
  						}

  						if (!file_exists($this->blankmaps . '/' . $this->map . '.' . $this->conf['Image Settings']['gd_ext']))
  						{
  									echo "WxWeb Error: map " . $this->blankmaps . "/" . $this->map . "." . $this->conf['Image Settings']['gd_ext'] .
  												" doesnt exit<br />\n";
  									return 0;
  						}
  						if (!file_exists($this->blankmaps . '/' . $this->map . '.txt'))
  						{
  									echo "WxWeb Error: map meta file" . $this->blankmaps . "/" . $this->map . ".txt doesnt exit<br />\n";
  									return 0;
  						}

  						$gd_ext = $this->conf['Image Settings']['gd_ext'];
  						//load projection
  						$this->maparray = parse_ini_file($this->blankmaps . '/' . $this->map . '.txt', true);

  						$this->mapplot = $this->projection->map_proj($this->maparray);

  						//load the map
  						if (strtoupper($gd_ext) == 'GIF')
  						{
  									$this->image = imagecreatefromgif($this->blankmaps . '/' . $this->map . '.' . $this->conf['Image Settings']['gd_ext']);
  						}
  						if (strtoupper($gd_ext) == 'PNG')
  						{
  									$this->map = imagecreatefrompng($this->blankmaps . '/' . $this->map . '.' . $this->conf['Image Settings']['gd_ext']);
  									$this->image = imagecreatetruecolor(imagesx($this->map), imagesy($this->map));

  									imagecopymerge($this->image, $this->map, 0, 0, 0, 0, imagesx($this->map), imagesy($this->map), 100);
  						}
  						if (strtoupper($gd_ext) == 'JPG')
  						{
  									$this->map = imagecreatefromjpeg($this->blankmaps . '/' . $this->map . '.' . $this->conf['Image Settings']['gd_ext']);
  									$this->image = imagecreatetruecolor(imagesx($this->map), imagesy($this->map));

  									imagecopymerge($this->image, $this->map, 0, 0, 0, 0, imagesx($this->map), imagesy($this->map), 100);
  						}


  						$this->image = $this->tools->add_underlay($this->image);

  						return $this->image;
  			}


  			function APIFinish($image, $timage)
  			{
  						foreach ($this->form as $key => $value)
  						{
  									if ($key == 'conf')
  												continue;
  									$this->{$key} = $value;
                                    
                                    if ($this->debug) print $this->{$key} ."= $value\n";

  									$this->{$key} = preg_replace('/\{(.+?)\|(\w+)\}/e', "\$this->in_format(\$this->variable_replace(\"$1\"), \"$2\");", $this->{$key} );

  									$this->{$key} = preg_replace('/\{(.+?)\}/e', "\$this->variable_replace(\"$1\");", $this->{$key} );
                                    
                                    
                        }


  						/** merge the two images **/
  						$image = ImageAPI::_copymerge($image, $timage);


  						/** add any overlays **/
  						$image = $this->tools->add_overlay($image);


  						/** add cities **/
  						$image = $this->plotter->do_cities($image, $this->mapplot);

  						/** water marks **/
  						$image = ImageAPI::_watermarks($image);

  						/** legend **/
  						$image = $this->legend->LegendSelect($image);

  						/** title **/
  						$image = ImageAPI::Title($image);

  						/** labels **/
  						$image = ImageAPI::_add_labels($image);


  			}


  			function transparency($image)
  			{

  						$trans = imagecreatetruecolor(imagesx($image), imagesx($image));
  						list($r, $g, $b) = $this->tools->getRGB('FFFFFA');
  						$offwhite = imagecolorallocate($trans, $r, $g, $b);

  						imagefilledrectangle($trans, 0, 0, imagesx($image), imagesy($image), $offwhite);
  						imagecolortransparent($trans, $offwhite);
  						imageantialias($trans, 'TRUE');

  						return array($offwhite, $trans);

  			}

  			function _copymerge($image, $timage)
  			{
  						$trans = (!empty($this->conf['Image Settings']['transparency'])) ? $this->conf['Image Settings']['transparency'] : '100';

  						imagecopymerge($image, $timage, 0, 0, 0, 0, imagesx($image), imagesy($image), $trans);

  						return $image;

  			}

  			function _watermarks($image)
  			{
  						$size = imagesx($image);

  						$watermark_path = (!empty($this->conf['Watermark Settings']['watermark_path'])) ? $this->conf['Watermark Settings']['watermark_path'] :
  									WATERMARKPATH;

  						$wmarks = (!empty($this->conf['Watermarks'])) ? $this->conf['Watermarks'] : '';

  						if (count($wmarks) > '0')
  						{

  									foreach ($wmarks as $key => $value)
  									{
  												if (preg_match('/' . $size . 'wm\d/', $key))
  												{
  															if ($this->debug)
  															{
  																		echo "ImageAPI::_watermarks $value<br />";
  															}
  															list($mimage, $mx, $my, $alpha) = explode("|", $value);
  															$image = $this->tools->doWatermark($image, $value);
  												}
  									}


  						}
  						return $image;

  			}

  			function Title($image)
  			{

  						$size = imagesx($image);
                        if (isset($this->credit))
                        {
                            $credit = $this->credit;
                        }
                        else
                        {
  						    $credit = (!empty($this->conf['Title Settings']['credittext'])) ? $this->conf['Title Settings']['credittext'] : $this->credit;
                        }
                        
                         if (isset($this->title))
                        {
                            $title = $this->title;
                        }
                        else
                        {
                            $title = (!empty($this->conf['Title Settings']['titletext'])) ? $this->conf['Title Settings']['titletext'] : $this->credit;
                        }

  						$credit = preg_replace('/\{(.+?)\|(\w+)\}/e', "\$this->in_format(\$this->variable_replace(\"$1\"), \"$2\");", $credit);
  						$title = preg_replace('/\{(.+?)\|(\w+)\}/e', "\$this->in_format(\$this->variable_replace(\"$1\"), \"$2\");", $title);

  						$credit = preg_replace('/\{(.+?)\}/e', "\$this->variable_replace(\"$1\");", $credit);
  						$title = preg_replace('/\{(.+?)\}/e', "\$this->variable_replace(\"$1\");", $title);
                        
                        if ($this->debug) print "Credit: $credit Title: $title\n";


  						$image = $this->tools->do_title($title, $credit, $image);

  						return $image;
  			}


  			function _add_labels($image)
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


  						//$local_time = $this->local_time;
  						$this->valid = date($this->conf['Local Time Settings']['format'], $this->valid);
  						$this->expire = date($this->conf['Local Time Settings']['format'], $this->expire);

  						$labels = $this->conf['Labels'];

  						foreach ($labels as $key => $value)
  						{
  									if (strtolower($key) == 'nodata')
  												continue;

  									list($label, $xx, $yy, $chest['ttfpt'], $chest['ttfcolor'], $chest['dsxoff'], $chest['dsyoff']) = explode("|", $value);
                                    $label = preg_replace('/\{(.+?)\}/e', "\$this->variable_replace(\"$1\");", $label);
                                    $label = preg_replace('/\{(.+?)\}/e' , "\$$1" , $label);
                                    
  									$image = $this->tools->do_text($image, $label, $xx, $yy, $chest);

  						}


  						return $image;

  			}


  			function variable_replace($var)
  			{
  						$result = (isset($this->form[$var])) ? $this->form[$var] : $this->{$var};

  						return $result;
  			}

  			function in_format($val, $format)
  			{

  						if ($format == 'capitalize')
  						{
  									$val = preg_replace("/(\w+)/e", "mb_convert_case('$1', MB_CASE_TITLE)", $val);
  						} elseif ($format == 'upper')
  						{
  									$val = strtoupper($val);
  						} elseif ($format == 'lower')
  						{
  									$val = strtolower($val);
  						} elseif ($format == 'round')
  						{
  									$val = round($val, 0) + 0;
  						}
  						return $val;
  			}

  			function thumbnail($timage, $savename)
  			{

  						if ($this->conf['Thumb Settings']['thumbs'] == '1')
  						{
  									$thumbs = $this->conf['Thumbnails'];
  									foreach ($thumbs as $key => $value)
  									{

  												list($thumbname, $th_w, $th_h, $th_ext, $th_comp) = explode("|", $value);

  												$thumb = imagecreatetruecolor($th_w, $th_h);
  												imagecopyresampled($thumb, $timage, 0, 0, 0, 0, $th_w, $th_h, imagesx($timage), imagesy($timage));

  												$thumbsavepath = (!empty($this->conf['Thumb Settings']['save_path'])) ? $this->conf['Thumb Settings']['save_path'] : $this->conf['Image Settings']['save_path'];

  												if ($this->debug)
  															echo "Thumbnail:: $thumbsavepath/" . $savename . $thumbname . "\n";

  												$this->tools->save_thumb($thumb, $savename . $thumbname, $thumbsavepath, $th_ext);


  									}
  						}

  						return;
  			}

  }

?>