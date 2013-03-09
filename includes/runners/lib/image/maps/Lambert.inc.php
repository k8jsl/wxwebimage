<?php

  /**
   * Author : Julien Moquet
   * 
   * Inspired by Proj4php from Mike Adair madairATdmsolutions.ca
   *                      and Richard Greenwood rich@greenwoodma$p->com 
   * License: LGPL as per: http://www.gnu.org/copyleft/lesser.html 
   * 
   * Adaptation for WxWeb By Jeff Lake MichiganWxSystem
   * 
   * // <!-- phpDesigner :: Timestamp -->7/18/2012 20:29:28<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Lambert.inc.php
   * @version 2
   * @revision .04
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */


  class Lambert
  {

  			var $EPSLN = 1.0e-10;
  			var $PI = 3.141592653589793238; //Math.PI,
  			var $HALF_PI = 1.570796326794896619; //Math.PI*0.5,
  			var $TWO_PI = 6.283185307179586477;


  			function __construct($info, $tools, $conf, $debug)
  			{
  						// array of:  r_maj,r_min,lat1,lat2,c_lon,c_lat,false_east,false_north
  						//double c_lat;                   /* center latitude                      */
  						//double c_lon;                   /* center longitude                     */
  						//double lat1;                    /* first standard parallel              */
  						//double lat2;                    /* second standard parallel             */
  						//double r_maj;                   /* major axis                           */
  						//double r_min;                   /* minor axis                           */
  						//double false_east;              /* x offset in meters                   */
  						//double false_north;             /* y offset in meters                   */
  						$this->info = $info;
  						$this->tools = $tools;
  						$this->conf = $conf;
  						$this->debug = $debug;

  						$this->k0 = '1.0';

  						$this->lat0 = deg2rad($info['rlat']);
  						$this->lat1 = deg2rad($info['sp1']);
  						$this->lat2 = deg2rad($info['sp2']);
  						$this->long0 = deg2rad($info['cm']);
  						$this->x0 = '0';
  						$this->y0 = '0';

  						$ll_lon = deg2rad($info['lllon']);
  						$ll_lat = deg2rad($info['lllat']);

  						$ur_lon = deg2rad($info['urlon']);
  						$ur_lat = deg2rad($info['urlat']);


  						$this->a = '6378249.2';
  						$this->b = '6356515';
  						$temp = $this->b / $this->a;
  						$this->e = sqrt(1.0 - $temp * $temp);

  						$sin1 = sin($this->lat1);
  						$cos1 = cos($this->lat1);


  						$ms = ($this->e * $sin1);
  						$ms1 = $cos1 / (sqrt(1.0 - $ms * $ms));
  						$ts1 = $this->tsfnz($this->e, $this->lat1, $sin1);

  						$sin2 = sin($this->lat2);
  						$cos2 = cos($this->lat2);
  						$ms2 = $this->msfnz($this->e, $sin2, $cos2);
  						$ts2 = $this->tsfnz($this->e, $this->lat2, $sin2);

  						$ts0 = $this->tsfnz($this->e, $this->lat0, sin($this->lat0));

  						if (abs($this->lat1 - $this->lat2) > '1.0e-10')
  						{
  									$this->ns = log($ms1 / $ms2) / log($ts1 / $ts2);
  						} else
  						{
  									$this->ns = $sin1;
  						}
  						$this->f0 = $ms1 / ($this->ns * pow($ts1, $this->ns));
  						$this->rh = $this->a * $this->f0 * pow($ts0, $this->ns);


  						list($this->lowleft_x, $this->lowleft_y) = $this->_lambert_fwd($ll_lon, $ll_lat);
  						list($this->uright_x, $this->uright_y) = $this->_lambert_fwd($ur_lon, $ur_lat);


  						$this->Xscale = ($this->uright_x - $this->lowleft_x) / ($info['width'] - 1);
  						$this->Yscale = ($this->uright_y - $this->lowleft_y) / ($info['height'] - 1);


  						// echo "new lright". $this->lright_y ." " . $this->lright_x ."<br />  uleft " . $this->uleft_y . " " . $this->uleft_x ."<br />";


  			}


  			/** lat long to x y **/
  			function LL_to_XY($lon, $lat)
  			{


  						// convert to radians
  						if ($lat <= 90.0 && $lat >= -90.0 && $lon <= 180.0 && $lon >= -180.0)
  						{
  									$lon = deg2rad($lon);
  									$lat = deg2rad($lat);
  						} else
  						{

  									return null;
  						}
  						list($lonm, $latm) = $this->_lambert_fwd($lon, $lat);

  						$x = floor(($lonm - $this->lowleft_x) / $this->Xscale + .5);
  						$y = floor(($latm - $this->lowleft_y) / $this->Yscale + .5);
  						$y = $this->info['height'] - $y;

  						return array($x, $y);
  			}

  			function XY_to_LL($px, $py)
  			{

  						$mx = ($px * $this->Xscale) + $this->lowleft_x;

  						$py = $this->info['height'] - $py;
  						$my = ($py * $this->Yscale) + $this->lowleft_y;


  						$rh1;
  						$con;
  						$ts;
  						$lat;
  						$lon;
  						$x = ($mx - $this->x0) / $this->k0;
  						$y = ($this->rh - ($my - $this->y0) / $this->k0);
  						if ($this->ns > 0)
  						{
  									$rh1 = sqrt($x * $x + $y * $y);
  									$con = 1.0;
  						} else
  						{
  									$rh1 = -sqrt($x * $x + $y * $y);
  									$con = -1.0;
  						}
  						$theta = 0.0;
  						if ($rh1 != 0)
  						{
  									$theta = atan2(($con * $x), ($con * $y));
  						}
  						if (($rh1 != 0) || ($this->ns > 0.0))
  						{
  									$con = 1.0 / $this->ns;
  									$ts = pow(($rh1 / ($this->a * $this->f0)), $con);
  									$lat = $this->phi2z($this->e, $ts);
  									if ($lat == -9999)
  												return null;
  						} else
  						{
  									$lat = -$this->HALF_PI;
  						}
  						$lon = $this->adjust_lon($theta / $this->ns + $this->long0);

  						$lat = rad2deg($lat);
  						$lon = rad2deg($lon);

  						$lat = round($lat, 3);
  						$lon = round($lon, 3);

  						return array($lon, $lat);
  			}


  			function phi2z($eccent, $ts)
  			{
  						$eccnth = .5 * $eccent;
  						$phi = $this->HALF_PI - 2 * atan($ts);
  						for ($i = 0; $i <= 15; $i++)
  						{
  									$con = $eccent * sin($phi);
  									$dphi = $this->HALF_PI - 2 * atan($ts * (pow(((1.0 - $con) / (1.0 + $con)), $eccnth))) - $phi;
  									$phi += $dphi;
  									if (abs($dphi) <= .0000000001)
  												return $phi;
  						}
  						assert("false; /* phi2z has NoConvergence */");
  						return (-9999);
  			}


  			/** do the constants needed for lat lon to x y meters **/
  			function _lambert_fwd($lon, $lat)
  			{

  						$con = abs(abs($lat) - $this->HALF_PI);
  						$ts;
  						$rh1;
  						if ($con > $this->EPSLN)
  						{
  									$ts = $this->tsfnz($this->e, $lat, sin($lat));
  									$rh1 = $this->a * $this->f0 * pow($ts, $this->ns);
  						} else
  						{
  									$con = $lat * $this->ns;
  									if ($con <= 0)
  									{

  												return null;
  									}
  									$rh1 = 0;
  						}
  						$theta = $this->ns * $this->adjust_lon($lon - $this->long0);
  						$x = $this->k0 * ($rh1 * sin($theta)) + $this->x0;
  						$y = $this->k0 * ($this->rh - $rh1 * cos($theta)) + $this->y0;


  						return array($x, $y);
  			}


  			function msfnz($eccent, $sinphi, $cosphi)
  			{
  						$con = $eccent * $sinphi;
  						return $cosphi / (sqrt(1.0 - $con * $con));
  			}

  			function tsfnz($eccent, $phi, $sinphi)
  			{
  						$con = $eccent * $sinphi;
  						$com = 0.5 * $eccent;
  						$con = pow(((1.0 - $con) / (1.0 + $con)), $com);
  						return (tan(.5 * ($this->HALF_PI - $phi)) / $con);
  			}

  			function adjust_lon($x)
  			{
  						$x = (abs($x) < $this->PI) ? $x : ($x - ($this->sign($x) * $this->TWO_PI));
  						return $x;
  			}
  			function sign($x)
  			{
  						if ($x < 0.0)
  									return (-1);
  						else
  									return (1);
  			}

  }
