<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('user_guides', function (Blueprint $table) {
            $table->string('route')->nullable()->after('file_path');
        });
    }
    public function down() {
        Schema::table('user_guides', function (Blueprint $table) {
            $table->dropColumn('route');
        });
    }
};

