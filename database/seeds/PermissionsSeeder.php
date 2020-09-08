<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Reset cached roles and permissions
       app()[PermissionRegistrar::class]->forgetCachedPermissions();

       // create permissions
       // products
       Permission::create(['name' => 'edit products']);
       Permission::create(['name' => 'delete products']);
       Permission::create(['name' => 'add products']);
       Permission::create(['name' => 'show products']);
       // users
       Permission::create(['name' => 'edit users']);
       Permission::create(['name' => 'delete users']);
       Permission::create(['name' => 'add users']);
       Permission::create(['name' => 'show users']);
       // categories
       Permission::create(['name' => 'edit categories']);
       Permission::create(['name' => 'delete categories']);
       Permission::create(['name' => 'add categories']);
       Permission::create(['name' => 'show categories']);
       // stores
       Permission::create(['name' => 'edit stores']);
       Permission::create(['name' => 'delete stores']);
       Permission::create(['name' => 'add stores']);
       Permission::create(['name' => 'show stores']);
       // orders
       Permission::create(['name' => 'edit orders']);
       Permission::create(['name' => 'delete orders']);
       Permission::create(['name' => 'add orders']);
       Permission::create(['name' => 'show orders']);
       Permission::create(['name' => 'refund orders']);
       // sliders
       Permission::create(['name' => 'edit sliders']);
       Permission::create(['name' => 'delete sliders']);
       Permission::create(['name' => 'add sliders']);
       Permission::create(['name' => 'show sliders']);
       // admins
       Permission::create(['name' => 'edit admins']);
       Permission::create(['name' => 'delete admins']);
       Permission::create(['name' => 'add admins']);
       Permission::create(['name' => 'show admins']);
       // roles
       Permission::create(['name' => 'edit roles']);
       Permission::create(['name' => 'delete roles']);
       Permission::create(['name' => 'add roles']);
       Permission::create(['name' => 'show roles']);

       // create roles and assign existing permissions
       $role1 = Role::create(['name' => 'admin']);
       $role1->givePermissionTo('edit roles');
       $role1->givePermissionTo('delete roles');
       $role1->givePermissionTo('add roles');
       $role1->givePermissionTo('show roles');

       $role2 = Role::create(['name' => 'super-admin']);
       // gets all permissions via Gate::before rule; see AuthServiceProvider

       // create admin
       $admin = Factory(App\Models\Admin::class)->create([
           'name' => 'Admin 01',
           'email' => 'ntam444@gmail.com',
           'password' => bcrypt('DRVuSB7D@aVVkGc'),
       ]);
       $admin->assignRole($role1);

       $admin = App\Models\Admin::where('email', 'admin@admin.com')->first();
       $admin->assignRole($role2);
    }
}
