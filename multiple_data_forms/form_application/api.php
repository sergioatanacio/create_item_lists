<?php
/* Se crea una función de array reduce, pero que permite usar la llave y el valor de un array. */
$array_reduce_key = function(array $array, callable $callback, $initial = [])
{
    $carry = $initial;
    foreach($array as $key => $value){
        $carry = $callback($carry, $key, $value);
    }
    return $carry;
};

$array_map_key = function(callable $callback, array $array) : array
{
    $return = [];
    foreach ($array as $key => $value) {
        $return[$key] = $callback($key, $value);
    }
    return $return;
};

$application_configuration = (fn(): array => require __DIR__.'./application_configuration.php')();


$name_file_relative = $application_configuration['file_with_array'];
$name_file = (fn():string =>__DIR__.'./../data_in_array/'.$name_file_relative.'.php')();

$header = fn(string $contend) : string =>
'<?php
return '.$contend.';';

$data_file = file_exists($name_file)
    ? require $name_file
    : null;

$data = (fn($arg) : array =>is_array($arg) ? $arg : ['default' => []])($data_file);

$actions = function(array $arg) use ($data, $array_map_key) : array
{
    $return = [
        'submit_item' => fn() : array => $array_map_key(
            fn($key, $value)=>
                ($key == $arg['list_to_item_add'])
                    ? array_merge($value, 
                        [[
                            'primero' => $arg['primero'],
                            'segundo' => $arg['segundo'],
                            'resolved' => false,
                        ]])
                    : $value, $data),
        'delete'    => fn() : array => $array_map_key(
            fn($key_list, $value_list)=>
                ($key_list == $arg['list_to_which_the_item_belongs'])
                    ? $array_map_key(
                        fn($key_item, $value_item)=> 
                            ($key_item == $arg['index_to_delete']) 
                                ? $array_map_key(fn($key_item_date, $value_item_date)=>
                                    ( $key_item_date == 'resolved') ? true : $value_item_date, $value_item) 
                                : $value_item, $value_list)
                    : $value_list, $data),
        'create_list' => function() use ($data, $arg)
            {
                $list_name = str_replace(' ', '_', strtolower(substr($arg['list_name'], 0, 20)));
                $name_of_list_to_join = (count(array_filter($data, fn($key)=> $key == $list_name, ARRAY_FILTER_USE_KEY)) < 1)
                    ? [ $list_name => [] ]
                    : [];
                return array_merge($data, $name_of_list_to_join);
            }
    ];
    return $return[$arg['type_form']]();
};


$data_more_post = (fn($arg_post): array =>
    ($arg_post !== [] && is_array($arg_post)) 
        /* El post es un array dentro de otro, por que debe unirse con el data, 
        el cual es un array que contiene arrays */
        ? $actions($arg_post)
        : $data)($_POST);




$write = (fn():string =>$header(var_export($data_more_post, true)))();


$file = fopen($name_file, "w");
fwrite($file, $write);
fclose($file);

print(json_encode($data_more_post));