### Laravel CRUD & View Generator

This package generates model, controllers, request, resource and views for CRUD operations.

This package is meant primarily for personal use.

#### Installation

Install the package in the development mode only. Since the package generates files, it isn't needed at all during
production.

```shell
composer require --dev adwiv/laravel-crud-daisyui
```

#### Usage

To use this generator, you must have an existing database table for the model and CRUD you want to generate. First,
create a migration, and migrate it to create the tables. Then, to generate all files use one of the following commands

```shell
php artisan crud:all Student
php artisan crud:all Student [--prefix admin]
php artisan crud:all Student [--route-prefix admin] [--view-prefix admin]
php artisan crud:all Student [--table students]
```


You can also generate individual files:
```shell
# Create Student model from students table
php artisan crud:model Student
# Create enums for all enum & set fields in students table
php artisan curd:enum Student
# Create the Request class for Student Model
php artisan crud:request StudentRequest
# Create the Resource class for Student Model
php artisan crud:resource StudentResource
# Create the Resource controller Student Model
php artisan crud:controller Admin/StudentController
# Create the API Resource controller Student Model
php artisan crud:controller Api/StudentController --api
# Create the index view for student model (admin/students/index.blade.php)
php artisan crud:view admin.students.index
# Create the index view for student model (admin/students/show.blade.php)
php artisan crud:view admin.students.show
# Create the index view for student model (admin/students/edit.blade.php)
php artisan crud:view admin.students.edit
```
