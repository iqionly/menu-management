(function(document) {
    const form = document.forms['menu-form'];
    
    const urlOriginal = menu_management.ajax.store_menu;
    const urlEditMenu = menu_management.ajax.store_menu;
    const urlDeleteMenu = menu_management.ajax.delete_menu;
    const urlBatchMenu = menu_management.ajax.batch_menu;
    const labelHeader = document.getElementById('menu-form-header');
    const eventResult = document.getElementById('event-result');
    const csrf = document.querySelector('[name=_token]').value;

    menu_management.event = 'adding_menu';

    const formRoute = $('#form-route').vselect({
        url: menu_management.ajax.list_routes_data,
        valueField: 'key',
        textField: 'value',
    });

    menu_management.setvalues = function(data) {
        if(data === null || data.id === undefined || data.id === null) {
            if(window.confirm(menu_management.language.confirmation_add_menu))
            {
                labelHeader.innerText = menu_management.language.add_new_menu;
                form.action = urlOriginal;
                eventResult.innerHTML = menu_management.language.adding_menu;
                menu_management.event = 'adding_menu';
                form.reset();
                return false;
            } else {
                return false;
            }
        }
        labelHeader.innerText = menu_management.language.edit_menu + ' ' + data.name;
        form.action = urlEditMenu + '/' + data.id;
        menu_management.event = 'editing_menu';

        document.getElementById('form-name').value = data.name ? data.name : '';
        // this.getElementById('form-parent').value = data.parent_id;
        // this.getElementById('after').value = data.name;
        document.getElementById('form-icon').value = data.icon ? data.icon : '';
        document.getElementById('form-description').value = data.description ? data.description : '';

        formRoute.setValue(data.route ?? '');

        return true;
    };

    document.getElementById('btn-add-menu').addEventListener('click', function() {
        document.dispatchEvent(new CustomEvent('add_menu'));
        return menu_management.setvalues(null);
    });

    document.getElementById('btn-del-menu').addEventListener('click', async function() {
        if(window.confirm(menu_management.language.confirmation_delete_menu)) {
            /**
             * This is for run code update each change happen to server, we use lazy update
             */
            // const form = new FormData()
            // form.set('_token', csrf);
            // const response = await fetch(urlDeleteMenu.replace(':id', menu_management.selected_node), {
            //     method: 'DELETE',
            //     headers: {
            //         'X-CSRF-TOKEN': csrf,
            //         'X-Requested-With': "XMLHttpRequest"
            //     },
            //     body: form
            // });

            // const result = await response.json();

            // if(response.ok) {
            //     document.dispatchEvent(new CustomEvent('success'));
            //     return response;
            // }
            // return response;

            document.dispatchEvent(new CustomEvent('delete_menu'));
        }
    });

    document.getElementById('btn-save-menu').addEventListener('click', async function() {
        /**
         * Ajax based
         */
        var obj = $('.menu-management-editor').jstree().get_json(undefined, {
            no_state: true,
            no_li_attr: true,
            no_a_attr: true,
        });

        $.ajax({
            url: urlBatchMenu,
            type: 'POST',
            data: {
                'menus': JSON.stringify(obj)
            },
            dataType: 'JSON',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': "XMLHttpRequest"
            },
            success: function(response) {
                if(response.message) {
                    document.dispatchEvent(new CustomEvent('success'));
                    alert(response.message);
                    return response;
                }
                return response;
            },
            error: function(response) {
                alert(response.message);
                return response;
            }
        });
    });
    
    document.addEventListener('state_changed', function() {
        console.log('editor changed detected!');

        document.getElementById('btn-save-menu').disabled = false;
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        document.dispatchEvent(new CustomEvent(menu_management.event, {
            detail: form
        }));
    });


})(document);