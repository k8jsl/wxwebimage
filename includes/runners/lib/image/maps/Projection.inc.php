<?php

 /**
   * // <!-- phpDesigner :: Timestamp -->7/18/2012 20:30:51<!-- /Timestamp -->
   * @author MichiganWxSystem/ByTheLakeWebDevelopment sales@michiganwxsystem.com
   * @copyright 2012
   * @package WxWebApi
   * @name Projection.inc.php
   * @version 2
   * @revision .05.1
   * @license http://creativecommons.org/licenses/by-sa/3.0/us/
   * 
   * 
   */

  class Projection
  {


  			function __construct($debug,$form,$conf,$tools)
  			{

  						$this->tools = $tools;
  						$this->conf = $conf;
  						$this->debug = $debug;
                        $this->form = $form;
  			}


  			function map_proj($maparray)
  			{
  						$proj = '';
  						$projection = ucfirst(strtolower(trim($maparray['projection'])));

  						require_once (RUNNERS . "/lib/image/maps/" . $projection . ".inc.php");

  						$proj = new $projection($maparray, $this->tools, $this->conf, $this->debug);

  						return $proj;


  			}


  }

?>
