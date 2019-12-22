<?php
$num_of_args = $argc;
array_shift($argv); //removes the unnecasary first arg
$args_array = $argv;
$required_args = array("--src", "--csv", "--out");
$filtered_args = array();

if(in_array("--info", $args_array)){
    echo "
        ======== Dictionary setter ===========
        please provide these three arguments:
        --src=(with the path to the source of the dictionary file)
        --csv=(with the path source of the csv file with the values to put in the dictionary)
        --out=(with the path to the required library to save the output in)

        You can use the --dry-run flag to test the output.

        The format of the CSV file shoud be as bellow:
        KEY   LNG1   LNG2   LNG3
        key1  val1   val2   val3
        ...   ...     ...   ...

        The first line holds the languages of the translations
        and the rest of the lines are for the key and the translations themselves.
    ";
}else{
    //exit if there are too many args
    if($num_of_args > 4 && !in_array("--dry-run", $args_array)){
        echo "Please pass the right amount of arguments, use --info for help";
        exit;
    }

    foreach($args_array as $arg){
        $exploded = explode("=", $arg);
        if(in_array($exploded[0], $required_args)){
            $filtered_args[$exploded[0]] = $exploded[1];
            $key = array_search($exploded[0], $required_args);
            unset($required_args[$key]);
        }
    }

    //decrease --out flag if we have a --dry-run flag
    if(in_array("--dry-run", $args_array)){
        $key = array_search('--out', $required_args);
        unset($required_args[$key]);
    }

    //exit if there aren't enough args
    if(count($required_args) !== 0){
        echo "Please pass all the required arguments, use --info for help";
        exit; 
    }

    //get csv file content
    $csv = array_map('str_getcsv', file($filtered_args['--csv']));
    //get json file content
    $string = file_get_contents($filtered_args['--src']);
    $dictionary = json_decode($string, true);

    //add the content from the csv to the dictionary
    for($i = 1; $i < count($csv); $i++){
        $key = array_shift($csv[$i]);
        foreach($csv[$i] as $lng=>$val){
            $dictionary[$key][$csv[0][$lng+1]] = $val;
        }
    }

    //get the output for a dry run
    if(in_array("--dry-run", $args_array)){
        print_r($dictionary);
        exit;
    }

    $output = json_encode($dictionary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents($filtered_args['--out'], $output);
    
}

