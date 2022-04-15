<?php
require __DIR__.'./code_inicio.php';

$first_template = function(string $contend_body, string $title = 'Document') :? string
{
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php print($title);?></title>
</head>
<body>
<?php print($contend_body);?>
</body>
</html>
<?php
    $return = ob_get_contents();
    ob_end_clean();
    return $return;
};

$first_form = function() use ($application_configuration) :? string 
{
    ob_start();
?>
    <h1>Create Item List</h1>
    <h2>Create list</h2>
    <form id="list_creation_form">
        <input type="hidden" name="type_form" value="create_list">
        <input autofocus type="text" name="list_name" value="" placeholder="Name of list" required id="id_creation_form">
        <input type="submit" value="create_list">
    </form>
    <p>* If the list name already exists, the list will not be created</p>
    <br>
    <h2>Add item</h2>
    <h3><?php print($application_configuration['file_with_array']);?></h3>
    <form id="form_insert_data">
        <input type="hidden" name="type_form" value="submit_item">
        <input type="hidden" name="list_to_item_add" value="tercer_elemento">
        
        <select class="class_list_to_item_add" name="list_to_item_add">
            <option selected="yes">default</option>
        </select>
        <p>Select the list you want to add an item to.</p><br>

        <label for=""><?php print($application_configuration['first_title']);?></label>
        <input class="editable_items" autofocus type="text" name="primero" value="" placeholder="primero" required>

        <label for=""><?php print($application_configuration['second_title']);?></label>
        <input class="editable_items" type="text" name="segundo" value="" placeholder="segundo">
        <input type="submit" value="Add item">
    </form>
        <br><br>
        <template id="template_table">
            <tr>
                <td class='primero'></td>
                <td class='segundo'></td>
                <td>
                    <form class="form_delete_to_data">
                        <input type="hidden" name="type_form" value="delete">
                        <input type="hidden" name="list_to_which_the_item_belongs" class="class_list_to_which_the_item_belongs">
                        <input type="hidden" name="index_to_delete" class="index_to_delete">
                        <input type="submit" value="Resolve">
                        <!-- <input type="submit" value="Eliminar" onclick="return confirm('¿Desea eliminar ... ?')"> -->
                    </form>
                </td>
            </tr>
        </template>
        <template id="template_table_category">
            <div class="table_to_show">
                <h3></h3>
                <table class="table_category">
                    <tr>
                        <th><?php print($application_configuration['first_title']);?></th>
                        <th><?php print($application_configuration['second_title']);?></th>
                        <th>Finalize</th>
                    </tr>
                </table>
            </div>
        </template>
        
        <div id="container_table_category"></div>

        <script src="inicio.js"></script>
<?php
    $return = ob_get_contents();
    ob_end_clean();
    
    return $return;
};

print($first_template($first_form(), 'Inicio'));



