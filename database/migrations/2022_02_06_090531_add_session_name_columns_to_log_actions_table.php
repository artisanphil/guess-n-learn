<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionNameColumnsToLogActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_actions', function (Blueprint $table) {
            $table->integer('round')->nullable()->after('id');
            $table->string('name')->nullable()->after('round');
            $table->string('session_id')->nullable()->after('IP');
            $table->string('mistakes')->default(0)->after('session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_actions', function (Blueprint $table) {
            $table->dropColumn('round');
            $table->dropColumn('name');
            $table->dropColumn('session_id');
            $table->dropColumn('mistakes');
        });
    }
}
