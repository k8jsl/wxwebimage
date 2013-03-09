{image}


NEWIMAGE:300|150|FFFFFF
RECTANGLE:0|0|300|17|DADADA|fill
TEXT:5|15|arial|12|000000|FFFFFF|Current Conditions
TEXT:5|32|arialbd|12|000000|FFFFFF|{$locname|capitalize}, {$state}

IMAGE:220|20|{$FILEPATH}images/wxicons/metar/{$metarwx_icon}
TEXT:295|102|arial|11|000080|FFFFFF|{$metarclouds_condition}|RIGHT
TEXT:295|117|arial|11|000080|FFFFFF|{$metarweather_condition|capitalize}|RIGHT
TEXT:295|140|arialbd|10|000000|FFFFFF|{$metarupdate}|RIGHT
TEXT:CENTER|85|bluehighwayfreebold|48|000000|dadada|{$metartemp_f}

TEXT:5|47|arial|10|000000|FFFFFF|Humidity: {$metarrel_humidity}
TEXT:5|62|arial|10|000000|FFFFFF|Dewpoint: {$metardew_f}°F
TEXT:5|77|arial|10|000000|FFFFFF|Wind Dir: {$metarwind_eng}
TEXT:5|92|arial|10|000000|FFFFFF|Wind Spd: {$metarwind_mph}
TEXT:5|107|arial|10|000000|FFFFFF|Wind Gst: {$metarwind_gust_mph}

{include file="includes/footer_img.tpl"}

{/image}