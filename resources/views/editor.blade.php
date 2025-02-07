<div class="container-fluids p-2">
    <div class="row">
        <div class="col">
            <div class="alert alert-primary event mb-0" id="event-result" role="alert">
                {{ trans('menu-management::messages.first_event') }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-1 mt-3">
            <h4>Menu Management</h4>
            <div class="mb-3">
                <button type="button" class="btn btn-success" id="btn-save-menu" disabled>{{ trans('menu-management::editor.save_state') }}</button>
                <button type="button" class="btn btn-primary" id="btn-add-menu">{{ trans('menu-management::editor.add_new_menu') }}</button>
                <button type="button" class="btn btn-danger" id="btn-del-menu" disabled>{{ trans('menu-management::editor.delete_menu') }}</button>
            </div>
            <div class="menu-management-editor border border-1 rounded"></div>

        </div>
        <div class="col-md-8 mt-3">
            <div class="card" id="menu-card">
                <div class="card-header">
                    <h4 id="menu-form-header">{{ trans('menu-management::editor.add_menu_title') }}</h4>
                </div>
                <div class="card-body">
                    <div class="col-md-6 mb-3 d-none default-field-param">
                        <label for="form-route" class="form-label" style="text-transform: capitalize;">{{ trans('menu-management::editor.required_params') }}</label>
                        <input type="text" name="params[]" placeholder="{{ trans('menu-management::editor.required_params_placeholder') }}" class="form-control">
                    </div>
                    
                    <form action="{{ route('menu-management.batch-menu') }}" name="menu-form" id="menu-form" method="post" class="form">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="form-name" class="form-label">{{ trans('menu-management::editor.add_menu_name') }}</label>
                                    <input type="text" id="form-name" name="name"
                                        placeholder="{{ trans('menu-management::editor.add_menu_name_placeholder') }}" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="form-icon" class="form-label">{{ trans('menu-management::editor.add_menu_icon') }}</label>
                                    <input type="text" id="form-icon" name="icon"
                                        placeholder="{{ trans('menu-management::editor.add_menu_icon_placeholder') }}" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="form-route" class="form-label">{{ trans('menu-management::editor.add_menu_route') }}</label>
                                    <select name="route" id="form-route" class="form-control" placeholder="{{ trans('menu-management::editor.add_menu_route') }}">
                                        <option value="">{{ trans('menu-management::editor.add_menu_route_placeholder') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="required-param">
                                
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="form-description" class="form-label">{{ trans('menu-management::editor.add_menu_description') }}</label>
                                    <textarea type="text" id="form-description" name="description" placeholder="{{ trans('menu-management::editor.add_menu_description_placeholder') }}"
                                        class="form-control" rows="10"></textarea>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card-footer text-end">
                    <button type="reset" class="btn btn-danger" form="menu-form">{{ trans('menu-management::editor.add_menu_reset') }}</button>
                    <button type="submit" class="btn btn-primary" id="menu-form-submit" form="menu-form">{{ trans('menu-management::editor.add_menu_save') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
