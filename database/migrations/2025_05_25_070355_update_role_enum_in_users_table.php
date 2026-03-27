<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateRoleEnumInUsersTable extends Migration
{
    public function up()
    {
        // For MySQL, you need to use raw SQL to modify ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'lulusan', 'pengguna_lulusan', 'supervisor') NOT NULL DEFAULT 'lulusan'");
    }

    public function down()
    {
        // Rollback to previous state (adjust as needed)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'lulusan', 'pengguna_lulusan', 'supervisor') NOT NULL DEFAULT 'lulusan'");
    }
}