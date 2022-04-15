
let table_category = document.querySelector('#table_category');
let template_table = document.querySelector('#template_table').content;
let form_insert_data = document.querySelector('#form_insert_data');
let template_table_category = document.querySelector('#template_table_category').content;
let list_creation_form = document.querySelector('#list_creation_form');

let add_event_to_delete = ()=>{
    
    let form_delete_to_data = document.querySelectorAll('.form_delete_to_data');
    form_delete_to_data.forEach((item) => {

        item.addEventListener('submit', (e) => {
            e.preventDefault();
            let element_to_delete = new FormData(item);
            fetch('api.php',{
                method: 'POST',
                body: element_to_delete,
            })
            .then( result => result.json)
            .then( data => {
                data_table_fn(new FormData());
            })
        });
    });
};

let fn_elements_of_lists = (fn_list, name_list) => {
    let fn_fragment = document.createDocumentFragment();

    for (let item in fn_list ) {
        if (fn_list[item].resolved == false) {
            template_table.querySelector('form input.class_list_to_which_the_item_belongs').setAttribute('value', name_list);

            template_table.querySelector('.primero').textContent = fn_list[item].primero;
            template_table.querySelector('.segundo').textContent = fn_list[item].segundo;
            template_table.querySelector('form input.index_to_delete').setAttribute('value', item);
    
            let clone_node = document.importNode(template_table, true);
            fn_fragment.append(clone_node);
        }
    };
    return fn_fragment;
};

let response_data_table_fn = (array_of_lists) => {
    let fragment = document.createDocumentFragment();
    let fragment_table_complete = document.createDocumentFragment();

    for (let lists in array_of_lists)
    {
        let elements_of_lists = array_of_lists[lists]

        fragment = fn_elements_of_lists(elements_of_lists, lists);

        let fragment_table_category = document.createDocumentFragment();
        clone_template_table_category = document.importNode(template_table_category, true);
        fragment_table_category.append(clone_template_table_category);
        
        fragment_table_category.querySelector('.table_to_show').classList.add(lists);
        fragment_table_category.querySelector('h3').append(lists);
        fragment_table_category.querySelector('.table_category').append(fragment);
    
        fragment_table_complete.append(fragment_table_category);
    };

    container_table_category.innerHTML = ''; 
    container_table_category.append(fragment_table_complete);
    /* form_insert_data.reset(); */
    form_insert_data.querySelectorAll('.editable_items').forEach(item =>{
        item.value = '';
    });

    add_event_to_delete();
};

let data_table_fn = (datos)=>
{
    fetch('api.php', {
        method: 'POST',
        body: datos
    })
    .then( res => res.json())
    .then( fetch_data => {
        console.log(fetch_data);
        response_data_table_fn(fetch_data);
        fn_table_to_show();
    })
    .catch(err => console.log(err));
};

form_insert_data.addEventListener('submit', (e)=>{
    e.preventDefault();
    data_table_fn(new FormData(form_insert_data));
});

data_table_fn(new FormData());

let class_list_to_item_add = document.querySelector('.class_list_to_item_add');

let fn_list_creation_form = data_list_creation_form => {
    let options_for_select = document.createDocumentFragment();
    for (let propiedad in data_list_creation_form)
    {
        let document_createElement_option = document.createElement("option");
        document_createElement_option.value = propiedad;
        document_createElement_option.textContent = propiedad;
        options_for_select.append(document_createElement_option);
    };
    class_list_to_item_add.textContent = '';
    class_list_to_item_add.append(options_for_select);

};

let with_fetch_list_creation_form = (form_data) => {
    fetch('api.php', {
        method: 'POST',
        body: form_data
    }).then(res => res.json())
    .then(fetch_data =>{
        fn_list_creation_form(fetch_data);
    })
    .catch(err => console.log(err));
    list_creation_form.querySelector('#id_creation_form').value = '';
};

list_creation_form.addEventListener('submit', (e)=>{
    e.preventDefault();
    with_fetch_list_creation_form(new FormData(list_creation_form));
});
with_fetch_list_creation_form(new FormData());

let fn_table_to_show = () => {
    let table_to_show = document.querySelectorAll('.table_to_show');
    table_to_show.forEach(item => {
        item.setAttribute("style", "display: none")
    });
    document.querySelector("."+class_list_to_item_add.value).setAttribute("style", "display: block");
};

class_list_to_item_add.addEventListener('change', ()=>{
    // alert('Hemos cambiado a '+class_list_to_item_add.value);
    fn_table_to_show();
});
