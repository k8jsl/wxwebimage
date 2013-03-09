<?php

  /**
   * // <!-- phpDesigner :: Timestamp -->7/18/2012 20:30:13<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Grid.inc.php
   * @version 1
   * @revision .00
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */

  class Grid
  {


  			function __construct($info, $tools, $conf, $debug)
  			{

  						$this->info = $info;
  						$this->tools = $tools;
  						$this->conf = $conf;
  						$this->debug = $debug;

  			}


  			function LL_to_XY($lon, $lat)
  			{
  						$info = $this->info;

  						$lon1 = $info['lon1'];
  						$lat1 = $info['lat1'];
  						$lon2 = $info['lon2'];
  						$lat2 = $info['lat2'];

  						if (($lon1 >= 0 && $lon2 >= 0) || ($lon1 <= 0 && $lon2 <= 0))
  						{
  									$xscale = abs(abs($lon1) - abs($lon2)) / $info['width'];
  						} elseif ($lon1 < 0 && $lon2 >= 0)
  						{
  									$xscale = abs(abs($lon1) + abs($lon2)) / $info['width'];
  						} else
  						{
  									$xscale = abs(abs(180 - $lon1) + abs($lon2)) / $info['width'];
  						}

  						if (($lat1 >= 0 && $lat2 >= 0) || ($lat1 <= 0 && $lat2 <= 0))
  						{
  									$yscale = abs(abs($lat1) - abs($lat2)) / $info['height'];
  						} else
  						{
  									$yscale = abs(abs($lat1) + abs($lat2)) / $info['height'];
  						}


  						if (($lon1 >= 0 && $lon2 >= 0))
  						{
  									$xloc = round(abs(abs($lon1) - abs($lon)) / $xscale);
  						} elseif (($lon1 <= 0 && $lon2 <= 0))
  						{
  									$xloc = round((abs($lon1) - abs($lon)) / $xscale);
  						} elseif ($lon1 < 0 && $lon2 >= 0)
  						{

  									if ($lon < 0)
  									{
  												$xloc = round((abs($lon1) - abs($lon)) / $xscale);
  									} else
  									{
  												$xloc = round((abs($lon1) + abs($lon)) / $xscale);
  									}
  						} else
  						{
  									$xloc = round((abs(180 - $lon1) + abs($lon)) / $xscale);
  						}

  						if (($lat1 >= 0 && $lat2 >= 0))
  						{
  									$yloc = round((abs($lat1) - abs($lat)) / $yscale);
  						} elseif (($lat1 <= 0 && $lat2 <= 0))
  						{
  									$yloc = round(abs(abs($lat1) - abs($lat)) / $yscale);
  						} elseif ($lat1 >= 0 && $lat2 < 0)
  						{
  									if ($lat > 0)
  									{
  												$yloc = round((abs($lat1) - abs($lat)) / $yscale);
  									} else
  									{
  												$yloc = round((abs($lat1) + abs($lat)) / $yscale);
  									}
  						}


  						return array($xloc, $yloc);

  			}


  			function XY_to_LL($x, $y)
  			{
  						$info = $this->info;

  						$lon1 = $info['lon1'];
  						$lat1 = $info['lat1'];
  						$lon2 = $info['lon2'];
  						$lat2 = $info['lat2'];


  						if (($lon1 >= 0 && $lon2 >= 0) || ($lon1 <= 0 && $lon2 <= 0))
  						{
  									$xdist = abs(abs($lon1) - abs($lon2)) / $info['width'];
  						} elseif ($lon1 < 0 && $lon2 >= 0)
  						{
  									$xdist = abs(abs($lon1) + abs($lon2)) / $info['width'];
  						} elseif ($lon1 >= 0 && $lon2 < 0)
  						{
  									$xdist = (180 - $lon1 + 180 - abs($lon2)) / $info['width'];
  						} else
  						{
  									$xdist = abs(abs(180 - $lon1) + abs($lon2)) / $info['width'];
  						}

  						if (($lat1 >= 0 && $lat2 >= 0) || ($lat1 <= 0 && $lat2 <= 0))
  						{
  									$ydist = abs(abs($lat1) - abs($lat2)) / $info['height'];
  						} else
  						{
  									$ydist = abs(abs($lat1) + abs($lat2)) / $info['height'];
  						}


  						$lon = ($x * $xdist) + $info['lon1'];
  						$lat = $info['lat1'] - ($y * $ydist);

  						return array($lon, $lat);
  			}
  }

?>
