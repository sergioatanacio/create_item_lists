<?php

$create_app_settings = function($file_to_create) : string
            {
                $write_application_configuration = (fn() : string =>
"<?php
return [
    'file_with_array'   => 'data',
    'first_title'       => 'Primero',
    'second_title'      => 'Segundo',
];")();
                $file = fopen($file_to_create, "w");
                fwrite($file, $write_application_configuration);
                fclose($file);
                return $file_to_create;
            };

$application_configuration = require (fn(string $file_application_configuration)=>
    (file_exists($file_application_configuration))
        ? (is_array(require $file_application_configuration)
            ? $file_application_configuration
            : $create_app_settings($file_application_configuration))
        : $create_app_settings($file_application_configuration))(__DIR__.'./application_configuration.php');
