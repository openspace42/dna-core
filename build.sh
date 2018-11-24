#!/usr/bin/env bash

echo "" > "dna.min"
filesList=( "./lib/author.php" "./lib/owner.php" "./lib/node_extend.php" "./lib/package_conf.php"  "./lib/fileSystem.php" "./lib/dna.php" "./dna" );

for i in "${filesList[@]}"
do
    mini=`php -w $i`
    mini=${mini/"<?php"/""}
    echo $mini >> "dna.min"

done

php -r '

    function delete_all_between($beginning, $end, $string) {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end, $beginningPos);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return delete_all_between($beginning, $end, str_replace($textToDelete, "", $string));
    }

    $handle = fopen("dna.min", "rb");
    $contents = fread($handle, filesize("dna.min"));
    fclose($handle);
    $temp="<?php \n";
    $temp .= delete_all_between("require_once" ,";",$contents);

    $myfile = fopen("dna.min", "w") or die("Unable to open file!");
    fwrite($myfile, $temp);
    fclose($myfile);
'
