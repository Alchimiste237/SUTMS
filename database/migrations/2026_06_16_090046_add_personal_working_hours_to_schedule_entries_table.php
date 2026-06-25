<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedule_entries', function (Blueprint $table) {
            $table->foreignId('teaching_assignment_id')->nullable()->change();
            $table->foreignId('teacher_id')->nullable()->constrained();
            $table->boolean('is_personal_working_hour')->default(false);
            $table->foreignId('class_group_id')->nullable()->constrained(); // Optional: link to class group if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_entries', function (Blueprint $table) {
            $table->foreignId('teaching_assignment_id')->nullable(false)->change();
            $table->dropConstrainedForeignId('teacher_id');
            $table->dropColumn('is_personal_working_hour');
            $table->dropConstrainedForeignId('class_group_id');
        });
    }
};
