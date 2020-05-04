<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFingerprintToFingerPrintInFeedbackRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_requests', function (Blueprint $table) {
            $table->renameColumn('fingerprint', 'finger_print');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedback_requests', function (Blueprint $table) {
            $table->renameColumn('finger_print', 'fingerprint');
        });
    }
}
