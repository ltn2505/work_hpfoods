<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        
        foreach ($departments as $department) {
            $deptName = $department->name;
            $deptId = $department->id;
            
            // Tạo 4 Employee cho mỗi phòng ban
            for ($i = 1; $i <= 4; $i++) {
                User::firstOrCreate([
                    'email' => "employee{$deptName}{$i}@gmail.com"
                ], [
                    'name' => "Employee {$deptName} {$i}",
                    'email' => "employee{$deptName}{$i}@gmail.com",
                    'password' => Hash::make('123@123a'),
                    'department_id' => $deptId,
                    'role' => 'employee',
                    'phone' => "0123456" . str_pad($deptId, 2, '0', STR_PAD_LEFT) . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'email_verified_at' => now(),
                ]);
            }
            
            // Tạo 2 Manager cho mỗi phòng ban
            for ($i = 1; $i <= 2; $i++) {
                User::firstOrCreate([
                    'email' => "manager{$deptName}{$i}@gmail.com"
                ], [
                    'name' => "Manager {$deptName} {$i}",
                    'email' => "manager{$deptName}{$i}@gmail.com",
                    'password' => Hash::make('123@123a'),
                    'department_id' => $deptId,
                    'role' => 'manager',
                    'phone' => "0123456" . str_pad($deptId, 2, '0', STR_PAD_LEFT) . str_pad($i + 4, 2, '0', STR_PAD_LEFT),
                    'email_verified_at' => now(),
                ]);
            }
        }
        
        // Tạo System Admin (nếu chưa có)
        User::firstOrCreate([
            'email' => 'admin@hpfoods.com'
        ], [
            'name' => 'System Admin',
            'email' => 'admin@hpfoods.com',
            'password' => Hash::make('123@123a'),
            'department_id' => null,
            'role' => 'admin',
            'phone' => '0123456789',
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('Users seeded successfully!');
        $this->command->info('Total users created: ' . User::count());
    }
}
