SAVEIMAGE:{$save_path}|{$locname|regex_replace:"/[\s]/":"_"}_{$state|lower}_{$run}.png
REDIRECT:{$save_url}|{$locname|regex_replace:"/[\s]/":"_"}_{$state|lower}_{$run}.png