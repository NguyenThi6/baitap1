<?php

use Illuminate\Database\Seeder;
use App\Admin;
use App\Roles;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();
        DB::table('admin_roles')->truncate();
        $adminRoles = Roles::where('name','admin')->first();
        $authorRoles = Roles::where('name','author')->first();
        $userRoles = Roles::where('name','user')->first();

        $admin = Admin::create([
			'admin_name' => 'Nhutthiadmin',
			'admin_email' => 'Nhutthiadmin@gmail.com',
			'admin_phone' => '0334444711',
			'admin_password' => md5('   ')	
        ]);
        $author = Admin::create([
			'admin_name' => 'Nhutthi123author',
			'admin_email' => 'Nhutthi123author@gmail.com',
			'admin_phone' => '0334444711',
			'admin_password' => md5('123456')	
        ]);
        $user = Admin::create([
			'admin_name' => 'huuphuocuser',
			'admin_email' => 'huuphuocuser@gmail.com',
			'admin_phone' => '0334444711',
			'admin_password' => md5('123456')
        ]);

        $admin->roles()->attach($adminRoles);
        $author->roles()->attach($authorRoles);
        $user->roles()->attach($userRoles);
        factory(App\Admin::class,20)->create();
    }
}
