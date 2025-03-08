<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Modify attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('user'); // Remove old 'user' column
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade'); // Add foreign key
        });

        // Modify overtimes table
        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropColumn('user'); // Remove old 'user' column
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade'); // Add foreign key
        });
    }

    public function down()
    {
        // Rollback changes if needed
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->string('user')->after('id'); // Restore old column
        });

        Schema::table('overtimes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->string('user')->after('id'); // Restore old column
        });
    }
};
