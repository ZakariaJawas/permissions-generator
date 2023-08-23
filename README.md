# Permissions Generator
<img src="https://img.shields.io/packagist/v/zakariajawas/permissions-generator.svg?style=flat-square" /> <img src="https://camo.githubusercontent.com/c0e68a5e33b5acc6165a845d9448c0094c3ce70eb393f365f1e3a3adb06672d5/68747470733a2f2f696d672e736869656c64732e696f2f7061636b61676973742f7068702d762f6e65757230746f78696e652f706f636b2e7376673f6c6f676f3d706870266c6f676f436f6c6f723d7768697465267374796c653d666c61742d737175617265" /> ![Licence](https://img.shields.io/github/license/ZakariaJawas/permissions-generator) ![Stars](https://img.shields.io/github/stars/ZakariaJawas/permissions-generator)

Generate basic permissions for spatie permissions package for all your models at once using one artisan command.

This package contains a `GeneratePermissionsProvider` that you can use in your packages to easily register the config file and the artisan command.

## What It Does
If you are using spatie permissions package, you need to create permissions for roles manually, common permissions for all models are access (list), create, edit and delete

This package will create all the basic permissions for all models using only one artisan command.

```php
php artisan permissions:generate
```

If you have the following models [Category, Product] as example, after running the generate command, the following rows will be inserted in your permissions table
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

You should publish the config/permissionsgenerator.php config file with:

```php
php artisan vendor:publish --provider="ZakariaJawas\PermissionsGenerator\GeneratePermissionsProvider"
```

### Working with config file
In config/permissionsgenerator.php file you can modify the following values.

- Modify this value to add, update or delete a permission.
 
 These are the basic permissions.
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
App\Models\Session::class, //no permissions generated for Session model
],
```

- If you have static permissions which are not related to models for example export to pdf or access all data or maybe a page which doesn't have a model you can add these permisisons in $staticPermissions array
```php
'staticPermissions' => ['export pdf', 'access dashboard']
```

Note:-

1) You have to add the full model class path.
2) You don't have to exclude other packages models, this package won't create permissions for them.

- Default permissions name is `permission + model name`, if you want to add a prefix you can specify it here.
```php
'prefix' => 'can',
```
Result will be `can create product` instead of `create product`


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Author
Zakaria Jawas [@zakariajawas](https://twitter.com/zakariajawas)

## Getting help
If you spot a problem you can open an issue on the Github page, or alternatively, you can contact me via `jawas.zakaria@gmail.com`

Support the library by twitting in  ![Twitter](https://img.shields.io/twitter/url?url=https%3A%2F%2Fgithub.com%2FZakariaJawas%2Fpermissions-generator%2F)

If you enjoy it, please make this library better :+1:
