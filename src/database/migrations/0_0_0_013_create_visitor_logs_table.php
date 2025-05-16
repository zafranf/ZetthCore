<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use DB;

class CreateVisitorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->string('id', 255);
            $table->string('ip', 45)->nullable();
            $table->string('page', 255);
            $table->string('referral', 255)->nullable();
            $table->string('agent', 255)->nullable();
            $table->string('browser', 255)->nullable();
            $table->string('browser_version', 255)->nullable();
            $table->string('device', 255)->nullable();
            $table->string('device_name', 255)->nullable();
            $table->string('os', 255)->nullable();
            $table->string('os_version', 255)->nullable();
            $table->enum('is_robot', ['yes', 'no'])->default('no');
            $table->string('robot_name', 255)->nullable();
            $table->unsignedInteger('count')->default(0);
            $table->unsignedInteger('site_id')->default(1);
            $table->dateTime('created_at', 3)->default(DB::raw('CURRENT_TIMESTAMP(3)'));
            $table->dateTime('updated_at', 3)->default(DB::raw('CURRENT_TIMESTAMP(3) ON UPDATE CURRENT_TIMESTAMP(3)'));

            $table->primary(['id', 'created_at', 'site_id']);
            $table->index(['created_at', 'site_id'], 'idx_created_site');
            $table->index(['updated_at', 'site_id'], 'idx_updated_site');
            $table->index('page', 'idx_page');
            $table->index('browser', 'idx_browser');
            $table->index('device', 'idx_device');
            $table->index('os', 'idx_os');
        });

        DB::statement("ALTER TABLE visitor_logs
            PARTITION BY RANGE COLUMNS(created_at) (
                PARTITION p202412 VALUES LESS THAN ('2025-01-01'),
                PARTITION p202501 VALUES LESS THAN ('2025-02-01'),
                PARTITION p202502 VALUES LESS THAN ('2025-03-01'),
                PARTITION p202503 VALUES LESS THAN ('2025-04-01'),
                PARTITION p202504 VALUES LESS THAN ('2025-05-01'),
                PARTITION p202505 VALUES LESS THAN ('2025-06-01'),
                PARTITION p202506 VALUES LESS THAN ('2025-07-01'),
                PARTITION p202507 VALUES LESS THAN ('2025-08-01'),
                PARTITION p202508 VALUES LESS THAN ('2025-09-01'),
                PARTITION p202509 VALUES LESS THAN ('2025-10-01'),
                PARTITION p202510 VALUES LESS THAN ('2025-11-01'),
                PARTITION p202511 VALUES LESS THAN ('2025-12-01'),
                PARTITION p202512 VALUES LESS THAN ('2026-01-01'),
                PARTITION p202601 VALUES LESS THAN ('2026-02-01'),
                PARTITION p202602 VALUES LESS THAN ('2026-03-01'),
                PARTITION p202603 VALUES LESS THAN ('2026-04-01'),
                PARTITION p202604 VALUES LESS THAN ('2026-05-01'),
                PARTITION p202605 VALUES LESS THAN ('2026-06-01'),
                PARTITION p202606 VALUES LESS THAN ('2026-07-01'),
                PARTITION p202607 VALUES LESS THAN ('2026-08-01'),
                PARTITION p202608 VALUES LESS THAN ('2026-09-01'),
                PARTITION p202609 VALUES LESS THAN ('2026-10-01'),
                PARTITION p202610 VALUES LESS THAN ('2026-11-01'),
                PARTITION p202611 VALUES LESS THAN ('2026-12-01'),
                PARTITION p202612 VALUES LESS THAN ('2027-01-01'),
                PARTITION p202701 VALUES LESS THAN ('2027-02-01'),
                PARTITION p202702 VALUES LESS THAN ('2027-03-01'),
                PARTITION p202703 VALUES LESS THAN ('2027-04-01'),
                PARTITION p202704 VALUES LESS THAN ('2027-05-01'),
                PARTITION p202705 VALUES LESS THAN ('2027-06-01'),
                PARTITION p202706 VALUES LESS THAN ('2027-07-01'),
                PARTITION p202707 VALUES LESS THAN ('2027-08-01'),
                PARTITION p202708 VALUES LESS THAN ('2027-09-01'),
                PARTITION p202709 VALUES LESS THAN ('2027-10-01'),
                PARTITION p202710 VALUES LESS THAN ('2027-11-01'),
                PARTITION p202711 VALUES LESS THAN ('2027-12-01'),
                PARTITION p202712 VALUES LESS THAN ('2028-01-01'),
                PARTITION p202801 VALUES LESS THAN ('2028-02-01'),
                PARTITION p202802 VALUES LESS THAN ('2028-03-01'),
                PARTITION p202803 VALUES LESS THAN ('2028-04-01'),
                PARTITION p202804 VALUES LESS THAN ('2028-05-01'),
                PARTITION p202805 VALUES LESS THAN ('2028-06-01'),
                PARTITION p202806 VALUES LESS THAN ('2028-07-01'),
                PARTITION p202807 VALUES LESS THAN ('2028-08-01'),
                PARTITION p202808 VALUES LESS THAN ('2028-09-01'),
                PARTITION p202809 VALUES LESS THAN ('2028-10-01'),
                PARTITION p202810 VALUES LESS THAN ('2028-11-01'),
                PARTITION p202811 VALUES LESS THAN ('2028-12-01'),
                PARTITION p202812 VALUES LESS THAN ('2029-01-01'),
                PARTITION p202901 VALUES LESS THAN ('2029-02-01'),
                PARTITION p202902 VALUES LESS THAN ('2029-03-01'),
                PARTITION p202903 VALUES LESS THAN ('2029-04-01'),
                PARTITION p202904 VALUES LESS THAN ('2029-05-01'),
                PARTITION p202905 VALUES LESS THAN ('2029-06-01'),
                PARTITION p202906 VALUES LESS THAN ('2029-07-01'),
                PARTITION p202907 VALUES LESS THAN ('2029-08-01'),
                PARTITION p202908 VALUES LESS THAN ('2029-09-01'),
                PARTITION p202909 VALUES LESS THAN ('2029-10-01'),
                PARTITION p202910 VALUES LESS THAN ('2029-11-01'),
                PARTITION p202911 VALUES LESS THAN ('2029-12-01'),
                PARTITION p202912 VALUES LESS THAN ('2030-01-01'),
                PARTITION p203001 VALUES LESS THAN ('2030-02-01'),
                PARTITION p203002 VALUES LESS THAN ('2030-03-01'),
                PARTITION p203003 VALUES LESS THAN ('2030-04-01'),
                PARTITION p203004 VALUES LESS THAN ('2030-05-01'),
                PARTITION p203005 VALUES LESS THAN ('2030-06-01'),
                PARTITION p203006 VALUES LESS THAN ('2030-07-01'),
                PARTITION p203007 VALUES LESS THAN ('2030-08-01'),
                PARTITION p203008 VALUES LESS THAN ('2030-09-01'),
                PARTITION p203009 VALUES LESS THAN ('2030-10-01'),
                PARTITION p203010 VALUES LESS THAN ('2030-11-01'),
                PARTITION p203011 VALUES LESS THAN ('2030-12-01'),
                PARTITION p203012 VALUES LESS THAN ('2031-01-01'),
                PARTITION pmax VALUES LESS THAN (MAXVALUE)
            )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE visitor_logs REMOVE PARTITIONING;");
    }
}
