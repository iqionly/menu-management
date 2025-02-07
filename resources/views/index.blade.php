<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MenuManagement {{ config('app.name') }}</title>

</head>

<body>

    <!-- Menu Navigation Preview -->
    <x-menu-management-navigation></x-menu-management-navigation>

    <x-menu-management-editor></x-menu-management-editor>

    @menu_management_js()
</body>

</html>
