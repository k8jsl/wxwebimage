<?php

/**
   * // <!-- phpDesigner :: Timestamp -->3/10/2013 12:14:38<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name ImageTemplate.inc.php
   * @version 1 
   * @revision .01.2
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   */

  require (LIBPATH . "/image/Tools.inc.php");
  require (LIBPATH . "/image/Legend.inc.php");
  require (LIBPATH . "/image/maps/Projection.inc.php");
  require (LIBPATH . "/image/Contour.inc.php");
  require (LIBPATH . "/image/Plotter.inc.php");
  require_once (LIBPATH . "/ConfigStruct.inc.php");
        

Class ImageTemplate extends WxWebAPI{

    var $template = '';
    var $image = '';
    var $VERSION = "WxWeb::ImageAPI -> ImageTemplate 2.02";
    var $commands = array(
    'NEWIMAGE' => 'new_image',
    'TEXT' => 'do_text',
    'LOADMAP' => 'load_map',
    'LATLON' => 'getXY',
    
    'IMAGE' => 'insert_image',
    'LINE' => 'do_line',
    'RECTANGLE' => 'do_rectangle',
    
    'SAVEIMAGE' => 'do_save',
    'REDIRECT' => 'do_redirect'
    
    );

           function __construct($runconf,$form,$database,$fetch,$conf,$debug,$mysql,$smarty)
  			{
  			          
			
  						
  						$this->fetch = $fetch;
                        $this->runconf = $runconf;
                        $this->form = $form;
                        $this->debug = $debug;
                        $this->conf = $conf;
                        $this->mysql = $mysql;
                        $this->smarty = $smarty;
                        $this->database = $database;
                        $this->combine = new ConfigStruct();
                        $imageconf = parse_ini_file(CONFIGS . "/wwimage.conf.php", TRUE);
                        
                        $this->conf = $this->combine->_arrayMergeRecursive($this->conf, $imageconf);
                        
                        

  						$this->tools = new Tools($this->debug, $this->form, $this->conf);
  						$this->projection = new Projection($this->debug, $this->form, $this->conf, $this->tools);
  						$this->contour = new Contour($this->debug, $this->form, $this->conf, $this->tools);
  						$this->legend = new Legend($this->debug, $this->form, $this->conf, $this->tools);
  						$this->plotter = new Plotter($this->debug, $this->form, $this->conf, $this->tools, $this->database, $this->mysql);
                        
                        $this->smarty->assign('save_path', $this->conf['Image Settings']['save_path']);
                        $this->smarty->assign('save_url', $this->conf['Image Settings']['save_url']);
                        

                        
                        
                        
             }
             
             
             
           function loadtemplate($params,$content)
           {
            
            
            
            $lines = preg_split("/[\r\n]/" , $content);
            
            foreach ($lines as $line)
            {
             if (!preg_match('/\w+/' , $line)) continue;
             if (preg_match('/<!--.+?-->/' , $line)) continue;
             
             if ($this->debug)
             {
                echo "WWImageTemplate:: $line\n"; 
             }
              
              if (preg_match('/^[A-Z]+\:/', $line))
              {
                
                list($func,$struct) = preg_split("/\:/" , $line);
                $function = $this->commands[$func];
                
                $this->image = $this->{$function}($this->image,$line);
                
                
                
              }
              elseif ($line == 'SAVEIMAGE')
              {
                $this->image = self::do_save($this->image,$line);
              }
              
              elseif ($line == 'REDIRECT')
              {
                $this->image = self::do_redirect($this->image,$line);
              }
            
             
            
            
           }
           
    }
    
    
    function new_image($image,$line)
    {
        
    $line = preg_replace("/NEWIMAGE\:/" , "" , $line);
    list($x,$y,$hex) = explode("|" , $line);
    
    
    
       
    $image = imagecreatetruecolor($x,$y);
    
    $bg = $this->tools->color($image,$hex);
      
    
    imagefilledrectangle($image,0,0,imagesx($image),imagesy($image),$bg);
    
    return $image;
    
    
    }
    
    
    
    function do_text($image,$line)
    {
        $line = preg_replace("/TEXT\:/" , "" , $line);
        $halign = '';
        
        $segments = explode("|", $line);
        
        list($x,$y,$font,$size,$hex,$dshex,$text) = $segments;
        
        if (sizeof($segments) > '7')
        {
            list($x,$y,$font,$size,$hex,$dshex,$text,$halign) = $segments;
        }
        
        $chest['ttfcolor'] = $hex;
        $chest['dscolor'] = $dshex;
        $chest['ttfpath'] = (!empty($this->conf['Image Settings']['TTF_path'])) ? $this->conf['Image Settings']['TTF_path'] : TTFPATH;
        $chest['ttfpt'] = $size;
        $chest['ttfangle'] = 0;
        $chest['ttffont'] = $font .".ttf";
        $chest['xoff'] = '0';
        $chest['yoff'] = '0';
        $chest['dsxoff'] = '1';
        $chest['dsyoff'] = '1';
        $chest['halign'] = $halign;
        $text = preg_replace("/<br\/>/i", " " , $text);
        
        $image = $this->tools->do_text($image,$text,$x,$y,$chest); 
        
        return $image;
    }
    
    function do_rectangle($image,$line)
    {
        $line = preg_replace("/RECTANGLE\:/" , "" , $line);
        
        list($x,$y,$px,$py,$hex,$fill) = explode("|" , $line);
        
        
        if (preg_match('/LAT\:(-?[\d\.]+)/' , $x, $m))
        {
            $lat = $m[1];
        }
        if (preg_match('/LON\:(-?[\d\.]+)/' , $y, $m))
        {
            $lon = $m[1];
        }
        
        list($x,$y) = $this->mapplot->LL_to_XY($lon,$lat);
        
        
        if (preg_match('/\+([\d]+)/' , $px, $m))
        {
            $px = $x + $m[1];
        }
        if (preg_match('/\+([\d]+)/' , $py, $m))
        {
            $py = $y + $m[1];
        }
        
        if ($this->debug) echo "X $x, Y $y, PX $px, PY $py\n";
        
        
        $color = $this->tools->color($image,$hex);
        
                
        if (is_numeric($fill))
        {
            imagesetthickness($image,$fill);
            
            imagerectangle($image,$x,$y,$px,$py,$color);
        }
        elseif (strtolower($fill) == 'fill')
        {
            imagefilledrectangle($image,$x,$y,$px,$py,$color);
        }
        
                
        return $image;
    }
    
    
    function insert_image($image,$line)
    {
        $line = preg_replace("/IMAGE\:/" , "" , $line);
        
        $resize_x = $resize_y = '0';
        $halign = '';
        $imgcommands = explode("|" , $line);
        
        if (count($imgcommands) == '3')
        {
            list($x,$y,$img) = $imgcommands;
        }
        else
        {
            list($x,$y,$img,$resize_x,$resize_y,$halign) = $imgcommands;
        }
         
        
        if (preg_match('/(.+?)\/(.+?)\.(\w{3,4})/' , $img, $tt))
        {
            $filepath = $tt[1];
            $insertimg = $tt[2];
            $exttype = $tt[3];
        }
        
        if ($this->debug)
        { 
            echo "WWImage InsertImage $filepath/$insertimg.$exttype at $x $y\n";
        }
        
                        if ($exttype == 'gif')
  						{
  									$newimage = imagecreatefromgif($filepath . "/" . $insertimg . ".gif");
                                    if ($resize_x > 0 && $resize_y > 0)
                                    {
                                        $newimage = $this->tools->resize($newimage,$resize_x,$resize_y,'gif');
                                    }

  						}

  						if ($exttype == 'png')
  						{
  									$newimage = imagecreatefrompng($filepath . "/" . $insertimg . ".png");
                                    if ($resize_x > 0 && $resize_y > 0)
                                    {
                                        $newimage = $this->tools->resize($newimage,$resize_x,$resize_y,'png');
                                    }
  						}

  						if ($exttype == 'jpeg' || $exttype == 'jpg')
  						{
  									$newimage = imagecreatefromjpeg($filepath . "/" . $insertimg . "." . $exttype);
                                    if ($resize_x > 0 && $resize_y > 0)
                                    {
                                        $newimage = $this->tools->resize($newimage,$resize_x,$resize_y,'jpeg');
                                    }

  						}
                        if (strtoupper($halign) == 'CENTER')
                        {
                            $x -= (imagesx($newimage) * .5);
                        }
                        imagecopy($image,$newimage,$x,$y,0,0,imagesx($newimage),imagesy($newimage));
                        imagedestroy($newimage);
                        return $image;       
        
        
        
        
        
        
        
    }
    
    
    
    
    function do_save($image,$line)
    {
       $line = preg_replace('/SAVEIMAGE\:/' , "", $line);
       
       list($filepath,$img) = explode("|" , $line);
       
       if (preg_match('/(.+?)\.(\w{3,4})$/' , $img, $t))
       {
        $savename = $t[1];
        $savetype = $t[2];
       }
       
       
                        
                        $filepath = (!empty($filepath)) ? $filepath : (!empty($this->conf['Image Settings']['save_path'])) ? $this->conf['Image Settings']['save_path'] : IMAGESAVEPATH;
  						
  						$savetype = (!empty($savetype)) ? $savetype : 'png';
  						$savename = (!empty($savename)) ? $savename : $this->form['name'];
  					
                      	$saveage = (!empty($this->conf['Image Settings']['save_age'])) ? $this->conf['Image Settings']['save_age'] : '15';
  						$nocache = (!empty($this->conf['Image Settings']['nocache'])) ? '1' : (!empty($this->form['nocache'])) ? '1' : '0';




                        if (file_exists($filepath . "/" . $savename . "." . $savetype) && time() - @filectime($filepath . "/" . $savename . "." . $savetype) <
  									$saveage && !$nocache)
  						{
  								return $image;	
  								
  						} else
  						{

  									if ($savetype == 'gif')
  									{
  									     if ($this->debug)
                                         {
                                            echo $filepath . "/" . $savename . "." . $savetype."\n";
                                            echo $fileurl . "/" . $savename . "." . $savetype . "\n";
                                         }
  												imagegif($image, $filepath . "/" . $savename . ".gif");
  												
  												return;
  									}

  									if ($savetype == 'png')
  									{
  									 if ($this->debug)
                                         {
                                            echo $filepath . "/" . $savename . "." . $savetype."\n";
                                            echo $fileurl . "/" . $savename . "." . $savetype . "\n";
                                         }
  												imagepng($image, $filepath . "/" . $savename . ".png");
  												return;
  									}

  									if ($savetype == 'jpeg')
  									{
  									 if ($this->debug)
                                         {
                                            echo $filepath . "/" . $savename . "." . $savetype."\n";
                                            echo $fileurl . "/" . $savename . "." . $savetype . "\n";
                                         }
  												imagejpeg($image, $filepath . "/" . $savename . ".jpeg");
  												
  												return;
  									}
  						}
                        
    
    
    }
    
    
    function do_redirect($image,$line)
    {
         $line = preg_replace('/REDIRECT\:/' , "", $line);
      
       list($fileurl, $file) = explode("|", $line);
       
       if (preg_match('/(.+?)\.(\w{3,4})$/' , $file, $tt))
        {
            
            $savename = $tt[1];
            $savetype = $tt[2];
            
        }
        
       
        
        
            $fileurl = (isset($fileurl)) ? $fileurl : $this->conf['Image Settings']['save_url'] ;
            $savetype = (isset($savetype)) ? $savetype : 'png';
  			$savename = (isset($savename)) ? $savename : $this->form['save'];
  		
          
          if ($this->debug)
        		echo "Redirecting: " . $fileurl ."/". $savename .".". $savetype."\n";
          
          	
            header("Location:" . $fileurl . "/" . $savename . "." . $savetype);
            return;
    }
    
    
    Function load_map($image,$line)
    {
        $line = preg_replace('/LOADMAP\:/' , "" , $line);
        
        
       
       if (preg_match('/(.+?)\/(.+?)\.(\w{3,4})/' , $line, $tt))
        {
            $filepath = $tt[1];
            $map = $tt[2];
            $type = $tt[3];
        }
        $maparray = parse_ini_file($filepath."/".$map.".txt");
        $this->mapplot = $this->projection->map_proj($maparray);
        
        $line = "IMAGE:0|0|" . $line;
        $image = self::insert_image($image,$line);
        return $image;
    }
    
    Function getXY($image,$line)
    {
        $line = preg_replace('/LATLON\:/' , "" , $line);
        list ($lat,$lon) = explode("|", $line);
        list($x,$y) = $this->mapplot->LL_to_XY($lon,$lat);
        $this->smarty->assign('mapX' , $x);
        $this->smarty->assign('mapY', $y);
        
        if ($this->debug) echo "get_XY $x , $y\n";
        
    }
    
    
    
}

?>
