<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add column as nullable first
        if (!Schema::hasColumn('job_issues', 'issue_no')) {
            Schema::table('job_issues', function (Blueprint $table) {
                $table->string('issue_no')->nullable()->after('id');
            });
        }
        
        // Generate issue numbers for existing records
        $existingCount = DB::table('job_issues')->whereNull('issue_no')->count();
        if ($existingCount > 0) {
            DB::statement("SET @row_number = 0");
            DB::statement("UPDATE job_issues SET issue_no = CONCAT('JI-', LPAD((@row_number:=@row_number + 1), 5, '0')) WHERE issue_no IS NULL");
        }
    }

    public function down()
    {
        Schema::table('job_issues', function (Blueprint $table) {
            if (Schema::hasColumn('job_issues', 'issue_no')) {
                $table->dropColumn('issue_no');
            }
        });
    }
};
