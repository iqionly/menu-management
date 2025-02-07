$(function () {
    $.jstree.defaults.core.themes.dots = true;
    $.jstree.defaults.core.multiple = false;    

    $(".menu-management-editor")
        .on("changed.jstree", function (e, data) {
            if(data.node === undefined || data.node === null) {
                return false;
            }

            var i, j, r = [];
            for (i = 0, j = data.selected.length; i < j; i++) {
                r.push(data.instance.get_node(data.selected[i]).text);
                menu_management.selected_node = data.instance.get_node(data.selected[i]).id;
            }
            $("#event-result").html("Selected: " + r.join(", "));
            

            if(data.selected.length > 0) {
                $('#btn-del-menu').attr('disabled', false);
            }

            menu_management.setvalues({
                id: data.node.id,
                name: data.node.text,
                icon: data.node.icon,
                description: data.node.data.description,
                route: data.node.data.route,
            });
        })
        .on("model.jstree", async function() {
            if(menu_management.state_data === undefined) {
                menu_management.state_data = await $(".menu-management-editor").jstree("get_json");
            }
        })
        .on("redraw.jstree", async function() {
            if(menu_management.state == 'changed') {
                document.dispatchEvent(new CustomEvent('state_changed'));
            }

        })
        .jstree({   
            "core": {
                "animation": 200,
                "check_callback": true,
                "themes": { "stripes": true },
                "data": {
                    "url": menu_management.ajax.list_menu_data,
                "data": function (node) {
                    return { id: node.id, children: node.children };
                }
            }
        },
        "plugins" : [
            "dnd", "search", "changed"
        ]
    });

    function toggleSaveMenu(enable = null) {
        $('#btn-save-menu').attr('disabled', typeof enable == "boolean" ? enable : !$('#btn-save-menu').attr('disabled'));
    }

    $(document).on("dnd_stop.vakata", function (e, data) {
        menu_management.state = 'changed';
        toggleSaveMenu(false);
    });

    $(document).on('success', function() {
        $(".menu-management-editor").jstree("refresh");

        menu_management.state = 'loaded';
        toggleSaveMenu(false);
    });

    $(document).on('delete_menu', function() {
        $(".menu-management-editor").jstree().delete_node(menu_management.selected_node);
        $(".menu-management-editor").jstree().deselect_all(true);

        menu_management.state = 'changed';
        toggleSaveMenu(false);
    });

    $(document).on('add_menu', function() {
        $(".menu-management-editor").jstree().deselect_all(true);
        $('#btn-del-menu').attr('disabled', true);
    })

    $(document).on('adding_menu', function(e) {
        const form = e.detail;
        const editor = $(".menu-management-editor").jstree();

        // Adding more fields if update the form
        const data = {
            text: $(form).find('#form-name').val(),
            icon: $(form).find('#form-icon').val(),
            data: {
                description: $(form).find('#form-description').val()
            }
        };
        menu_management.state = 'changed';
        toggleSaveMenu(false);

        editor.create_node('#', data);

        form.reset();

    });

    $(document).on('editing_menu', function(e) {
        const form = e.detail;
        const editor = $(".menu-management-editor").jstree();

        const selectedNode = editor.get_selected();

        editor.get_node(selectedNode)['text'] = $(form).find('#form-name').val();
        editor.get_node(selectedNode)['icon'] = $(form).find('#form-icon').val() == 'true' ? true : $(form).find('#form-icon').val();
        editor.get_node(selectedNode)['data']['description'] = $(form).find('#form-description').val();
        editor.get_node(selectedNode)['data']['route'] = $(form).find('#form-route').val();

        menu_management.state = 'changed';
        toggleSaveMenu(false);

        editor.redraw(true);
    });
});