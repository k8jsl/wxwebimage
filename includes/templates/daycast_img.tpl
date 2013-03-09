{image}

NEWIMAGE:380|200|FFFFFF

TEXT:CENTER|18|arialbd|12|000000|FFFFFF|{$locname|capitalize}, {$state}|CENTER

{$offset = 60}

{for $i = 0 to 4}
TEXT:{$i * 65 + $offset}|54|arialbd|11|000000|dadada|{$DayNames[$i]|capitalize}|SPACEWRAP
{/for}


{for $i=0 to 4}
IMAGE:{$i * 65 + $offset}|70|{$FILEPATH}images/wxicons/wxdir/wxdesk48/{$Dayicon[$i]}|45|33|CENTER
{/for}


{for $i=0 to 4}
TEXT:{$i * 65 + $offset}|115|arial|10|000000|cacaca|{$Daywx[$i]|capitalize}|SPACEWRAP
{/for}

{for $i=0 to 4}
{if $Dayhi[$i]}
TEXT:{$i * 65 + $offset}|180|arialbd|12|ff0000|000000|{$Dayhi[$i]}|CENTER
{/if}
{if $Daylo[$i]}
TEXT:{$i * 65 + $offset}|180|arialbd|12|066AF4|000000|{$Daylo[$i]}|CENTER
{/if}
{/for}



{include file="includes/footer_img.tpl"}

{/image}