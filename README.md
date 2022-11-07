# Permissions Generator
Generate basic permissions for spatie permissions package for all your models at once using one artisan command.

This package contains a `GeneratePermissionsProvider` that you can use in your packages to easily register the config file and the artisan command.

## What It Does
If you are using spatie permissions package, you need to create permissions for roles manually, common permissions for all models are access (list), create, edit and delete

This package will create all the base permissions for all models using only one artisan command.

```php
php artisan permissions:generate
```

If you have the following models [Category, Product], after running the generate command, the following rows will be inserted in your permissions table
```
list category
create category
edit category
delete category

list product
create product
edit product
delete product
```
Note: You can modify some values inside the config file, check **Working with config file** section below.

## Getting started
### Installation
```php
composer require zakariajawas/permissions-generator
```

Add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    \ZakariaJawas\PermissionsGenerator\Providers\GeneratePermissionsProvider::class,
];
```

You should publish the config/permissionsgenerator.php config file with:

```php
php artisan vendor:publish --provider="ZakariaJawas\PermissionsGenerator\Providers\GeneratePermissionsProvider"
```

### Working with config file
In config/permissionsgenerator.php file you can modify the following values.

- You can modify the following value to add, update or delete a permission.
 
 There are the basic permissions.
```php
'permissions' => ['list', 'create', 'edit', 'delete']
```
For example to add **access** permission and change **edit** to **update** you can do this.
```php
'permissions' => ['access', 'list', 'create', 'update', 'delete']
```

- By default the package will generate the permissions for all project models, you might want to exclude specific one or more models so you can do this.
```php
'exclude' => [
App\Models\Sessions::class,
],
```
Note:-

1) You have to add the full model class path.
2) You don't have to exclude other packages models, the package won't create permissions for them.

- Default permissions name is the `permission + model name`, if you want to add a prefix you can specify it here.
```php
'prefix' => 'can',
```
Result will be `can access product` instead of `access product`


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
