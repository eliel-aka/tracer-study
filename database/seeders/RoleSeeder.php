<?php

namespace Database\Seeders;

use App\Models\Lulusan;
use App\Models\PenggunaLulusan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating roles and demo users...');
        
        // Create all roles first
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $lulusanRole = Role::firstOrCreate(['name' => 'lulusan']);
        $penggunaLulusanRole = Role::firstOrCreate(['name' => 'penggunaLulusan']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        
        $this->command->info('✅ Roles created: admin, lulusan, penggunaLulusan, supervisor');

        // Create Admin User
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123123123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);

        // Create Lulusan User
        $lulusanUser = User::firstOrCreate(
            ['email' => 'lulusan@lulusan.com'],
            [
                'name' => 'Lulusan User',
                'email' => 'lulusan@lulusan.com',
                'password' => Hash::make('123123123'),
                'role' => 'lulusan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $lulusanUser->assignRole($lulusanRole);

        // Create Lulusan profile
        Lulusan::firstOrCreate(
            ['user_id' => $lulusanUser->id],
            [
                'user_id' => $lulusanUser->id,
                'nama' => 'Lulusan Demo',
                'no_hp' => '08123456789',
                'nip' => '199001012015011001',
                'nip_baru' => '199001012015011001',
                'nip_lama' => '340012345',
                'jabatan' => 'Staff',
                'satuan_kerja' => 'Divisi IT',
                'unit_kerja' => 'Pengembangan',
                'email' => 'lulusan@gmail.com',
                'tanggal_lahir' => '1990-01-01',
                'nip_pengguna_lulusan' => '198001012005011001',
                'nip_baru_pengguna_lulusan' => '198001012005011001',
                'nip_lama_pengguna_lulusan' => '340054321',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Pengguna Lulusan User
        $penggunaLulusanUser = User::firstOrCreate(
            ['email' => 'penggunaLulusan@penggunaLulusan.com'],
            [
                'name' => 'Pengguna Lulusan User',
                'email' => 'penggunaLulusan@penggunaLulusan.com',
                'password' => Hash::make('123123123'),
                'role' => 'pengguna_lulusan',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $penggunaLulusanUser->assignRole($penggunaLulusanRole);

        // Create Pengguna Lulusan profile
        PenggunaLulusan::firstOrCreate(
            ['user_id' => $penggunaLulusanUser->id],
            [
                'user_id' => $penggunaLulusanUser->id,
                'nama' => 'Pengguna Lulusan Demo',
                'jabatan' => 'Manager',
                'satuan_kerja' => 'Divisi IT',
                'unit_kerja' => 'Pengembangan',
                'email' => 'penggunaLulusan@gmail.com',
                'no_hp' => '08123456789',
                'nip' => '198001012005011001',
                'nip_baru' => '198001012005011001',
                'nip_lama' => '340054321',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create Supervisor User
        $supervisorUser = User::firstOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'name' => 'Supervisor User',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('123123123'),
                'role' => 'supervisor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $supervisorUser->assignRole($supervisorRole);

        $this->command->info('✅ Demo users created with credentials:');
        $this->command->info('👑 Admin: admin@admin.com / 123123123');
        $this->command->info('🎓 Lulusan: lulusan@lulusan.com / 123123123');
        $this->command->info('👔 Pengguna Lulusan: penggunaLulusan@penggunaLulusan.com / 123123123');
        $this->command->info('📊 Supervisor: supervisor@example.com / 123123123');
        $this->command->info('');
        $this->command->info('🎉 All roles and demo users have been created successfully!');
    }
}
