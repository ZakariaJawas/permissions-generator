<?php 
return [

     /*
    |--------------------------------------------------------------------------
    | Permission Prefix
    |--------------------------------------------------------------------------
    |
    | This option will add a prefix for all permissions
    | All permissions will be in the form of [permission] [model name]
    | setting a value to prefix will allow permissions to have a prefix as [prefix] [permission] [model name]
    |
    | Example
    | default permission is [create user]
    | With Prefix 'can'
    | permission is [can create user]
    */
    'prefix' => '',

    /*
    |--------------------------------------------------------------------------
    | Exclude Models
    |--------------------------------------------------------------------------
    |
    | This option will instruct the generator to exclude some models
    | from permissions generating.    
    |
    | Example
    | to exclude token model for example, add the model full class path to the following array
    | 'exclude' => [App\Models\Token::class]
    */
    'exclude' => [],
    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | This option contains permissions the generator will use to create 
    | Default permissions are list, create, edit and delete
    | 
    | To add, modify or delete a permission you can edit the following array
    |
    | Example
    | to change edit to update
    | 'permissions' => ['list', 'create', 'update', 'delete']
    | to add access permission 
    | 'permissions' => ['access', 'list', 'create', 'update', 'delete']
    */
    'permissions' => ['list', 'create', 'edit', 'delete']

];