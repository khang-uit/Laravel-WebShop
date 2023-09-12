<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Roles;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       User::truncate();

        $adminRoles = Roles::where('name','admin')->first();
        $authorRoles = Roles::where('name','author')->first();
        $userRoles = Roles::where('name','user')->first();

        $admin = User::create([
			'email' => 'hieutan@gmail.com',
			'password' => bcrypt('123456')	
        ]);
        $author =User::create([
			'email' => 'hieutan123@gmail.com',
			'password' => bcrypt('123456')	
        ]);
        $user =User::create([
			'email' => 'hieutan456@gmail.com',
			'password' => bcrypt('123456')
        ]);

        $admin->roles()->attach($adminRoles);
        $author->roles()->attach($authorRoles);
        $user->roles()->attach($userRoles);
    }
}
