<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index single-column pada kolom teks tabel log tidak pernah dipakai planner:
 * pencarian admin memakai LIKE '%kata%' (wildcard di depan) sehingga EXPLAIN
 * selalu type=ALL. Index-nya hanya menambah biaya tulis dan ukuran tabel.
 *
 * Terukur di dev (2026-10-23): visitor_logs index 445 MB > data 261 MB.
 */
class DropUnusedLogIndexes extends Migration
{
    public function up()
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->dropIndex('idx_page');
            $table->dropIndex('idx_browser');
            $table->dropIndex('idx_device');
            $table->dropIndex('idx_os');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('idx_description');
            $table->dropIndex('idx_path');
        });
    }

    public function down()
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->index('page', 'idx_page');
            $table->index('browser', 'idx_browser');
            $table->index('device', 'idx_device');
            $table->index('os', 'idx_os');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('description', 'idx_description');
            $table->index('path', 'idx_path');
        });
    }
}
