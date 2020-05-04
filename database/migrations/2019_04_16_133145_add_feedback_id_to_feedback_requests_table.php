<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeedbackIdToFeedbackRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('feedback_id')->after('id')->nullable();
            $table->foreign('feedback_id')->references('id')->on('feedback');
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
            $table->dropForeign('feedback_requests_feedback_id_foreign');
            $table->dropColumn('feedback_id');
        });
    }
}
