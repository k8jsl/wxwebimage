<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->11/19/2012 13:08:59<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Contour.inc.php
   * @version 1
   * @revision .04
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */
  class Contour
  {

  			var $VERSION = "WxWeb Image Contour.inc.php 3.0 11/19/2012 13:08:57<br />";

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



  			function set_colors($contour_colors)
  			{
  						
  						$steps = (isset($this->conf['Color Settings']['Contour_steps'])) ? $this->conf['Color Settings']['Contour_steps'] : '10';
                        
                        

  						$tcolors = array();
  						foreach ($contour_colors as $key => $value)
  						{
  									$key = preg_replace("/B/", "-", $key);
  									$tcolors[$key] = $value;

  						}
  						ksort($tcolors, SORT_NUMERIC);
  						reset($tcolors);
                        if($this->debug) echo "Steps $steps\n";
                        
                        if ($steps == '1')
                        {
                            $goods = array_values($tcolors);
                            $combinedArray = array();
  						    $keyCount = count($tcolors);
  						    foreach ($tcolors as $key => $color)
  						    {
  								$combinedArray[$key] = $color;
  						    }
                        
                        if ($this->debug) print_r($combinedArray);

  						return $combinedArray;
        
                        }
                        
                        else
                        {


  						$tmin = key($tcolors);
  						end($tcolors);
  						$tmax = key($tcolors);
  						//$tcolors = null;
  						$y = 1;
  						$temp = $tmin;
  						$grad_colors = $temps = array();
  						foreach ($tcolors as $key => $color)
  						{

  									$ntmin = $tmin + $steps * $y;
  									if ($ntmin <= $tmax)
  									{
                                    array_push($grad_colors, array_values($this->gradient($tcolors[$key], $tcolors[$ntmin], $steps)));
  									}

  									$y++;
  						}


  						$good = array();
                        
                        

  						foreach ($grad_colors as $set)
  						{
  									$t = 0;
  									foreach ($set as $colors)
  									{

  												array_push($good, $colors);

  									}
  						}
  						$good = array_unique($good);
  						$goods = array_values($good);
                        
                        


  						for ($i = $tmin; $i <= $tmax; $i++)
  						{
  									array_push($temps, $i);

  						}


  						if ($this->debug)
  						{
  									print "First: " . count($good) . "\n";
  									print "Unique: " . count($goods) . "\n";
  									print "Contour:" . count($temps) . "\n";
  						}


  						$combinedArray = array();
  						$keyCount = count($temps);
  						for ($i = 0; $i < $keyCount; $i++)
  						{
  						    if ($this->debug) print $temps[$i]."=".$goods[$i]."\n";
  									$combinedArray[$temps[$i]] = $goods[$i];
  						}
                        
                        
                        if ($this->debug) print_r($combinedArray);
  						return $combinedArray;
                        
                        }


  			}


  			function gradient($hexstart, $hexend, $steps)
  			{

  						$start['r'] = hexdec(substr($hexstart, 0, 2));
  						$start['g'] = hexdec(substr($hexstart, 2, 2));
  						$start['b'] = hexdec(substr($hexstart, 4, 2));

  						$end['r'] = hexdec(substr($hexend, 0, 2));
  						$end['g'] = hexdec(substr($hexend, 2, 2));
  						$end['b'] = hexdec(substr($hexend, 4, 2));

  						$step['r'] = ($start['r'] - $end['r']) / ($steps);
  						$step['g'] = ($start['g'] - $end['g']) / ($steps);
  						$step['b'] = ($start['b'] - $end['b']) / ($steps);

  						$gradient = array();

  						for ($i = 0; $i <= $steps; $i++)
  						{

  									$rgb['r'] = floor($start['r'] - ($step['r'] * $i));
  									$rgb['g'] = floor($start['g'] - ($step['g'] * $i));
  									$rgb['b'] = floor($start['b'] - ($step['b'] * $i));

  									$rgb['r'] = preg_replace('/-/', "", $rgb['r']);
  									$rgb['b'] = preg_replace('/-/', "", $rgb['b']);
  									$rgb['g'] = preg_replace('/-/', "", $rgb['g']);


  									$hex['r'] = sprintf('%02x', ($rgb['r']));
  									$hex['g'] = sprintf('%02x', ($rgb['g']));
  									$hex['b'] = sprintf('%02x', ($rgb['b']));

  									$gradient[] = implode(null, $hex);


  						}

  						return $gradient;

  			}

  }

?>