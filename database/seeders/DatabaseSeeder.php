<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('abc123'),
        ]);

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('abc1234'),
        ]);

        User::factory()->create([
            'name' => 'Satpam',
            'email' => 'satpam@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'Operator',
            'email' => 'operator@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'Penerima',
            'email' => 'penerima@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'Pembenihan',
            'email' => 'pembenihan@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'SDM',
            'email' => 'sdm@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'Keuangan dan Akutansi',
            'email' => 'keuanganakutansi@gmail.com',
            'password' => bcrypt('123'),
        ]);

        User::factory()->create([
            'name' => 'Pemasaran Ekspor',
            'email' => 'pemasaranekspor@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Pemasaran Domestik',
            'email' => 'pemasarandomestik@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Sekper',
            'email' => 'sekper@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Riset dan Pengembangan',
            'email' => 'risetdanpengembangan@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Pengolahan',
            'email' => 'pengolahan@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Teknik dan Pemeliharaan',
            'email' => 'teknikdanpemeliharaan@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Budidaya (on farm)',
            'email' => 'budidaya@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'SPI',
            'email' => 'spi@gmail.com',
            'password' => bcrypt('123'),
        ]);
        User::factory()->create([
            'name' => 'Pengadaan',
            'email' => 'pengadaan@gmail.com',
            'password' => bcrypt('123'),
        ]);


        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::create(['name' => 'satpam', 'guard_name' => 'web']);
        Role::create(['name' => 'operator', 'guard_name' => 'web']);
        Role::create(['name' => 'penerima', 'guard_name' => 'web']);

        $admin = User::where('email', 'admin@gmail.com')->first();
        $admin->assignRole('admin');

        $superAdmin = User::where('email', 'superadmin@gmail.com')->first();
        $superAdmin->assignRole('super_admin');

        $satpam = User::where('email', 'satpam@gmail.com')->first();
        $satpam->assignRole('satpam');

        $operator = User::where('email', 'operator@gmail.com')->first();
        $operator->assignRole('operator');

        $penerima = User::where('email', 'penerima@gmail.com')->first();
        $penerima->assignRole('penerima');

        $pembenihan = User::where('email', 'pembenihan@gmail.com')->first();
        $pembenihan->assignRole('penerima');

        $sdm = User::where('email', 'sdm@gmail.com')->first();
        $sdm->assignRole('penerima');

        $keuanganakutansi = User::where('email', 'keuanganakutansi@gmail.com')->first();
        $keuanganakutansi->assignRole('penerima');

        $pemasaranekspor = User::where('email', 'pemasaranekspor@gmail.com')->first();
        $pemasaranekspor->assignRole('penerima');

        $pemasarandomestik = User::where('email', 'pemasarandomestik@gmail.com')->first();
        $pemasarandomestik->assignRole('penerima');

        $sekper = User::where('email', 'sekper@gmail.com')->first();
        $sekper->assignRole('penerima');

        $risetdanpengembangan = User::where('email', 'risetdanpengembangan@gmail.com')->first();
        $risetdanpengembangan->assignRole('penerima');

        $pengolahan = User::where('email', 'pengolahan@gmail.com')->first();
        $pengolahan->assignRole('penerima');

        $teknikdanpemeliharaan = User::where('email', 'teknikdanpemeliharaan@gmail.com')->first();
        $teknikdanpemeliharaan->assignRole('penerima');

        $budidaya = User::where('email', 'budidaya@gmail.com')->first();
        $budidaya->assignRole('penerima');

        $spi = User::where('email', 'spi@gmail.com')->first();
        $spi->assignRole('penerima');

        $pengadaan = User::where('email', 'pengadaan@gmail.com')->first();
        $pengadaan->assignRole('penerima');

        // Seeder untuk Master Data
        $this->call([
            DivisiSeeder::class,
            StatusSeeder::class,
            VisitStatusSeeder::class,
        ]);
    }
}
