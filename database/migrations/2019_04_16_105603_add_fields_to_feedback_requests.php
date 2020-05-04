<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToFeedbackRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedback_requests', function (Blueprint $table) {
            $table->ipAddress('ip')->nullable()->after('branch');
            $table->timestamp('completed_on')->nullable()->after('ip');
            $table->string('fingerprint')->nullable()->after('completed_on');
            $table->text('user_agent')->nullable()->after('fingerprint');
            $table->softDeletes();
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
            $table->dropColumn('ip');
            $table->dropColumn('completed_on');
            $table->dropSoftDeletes();
        });
    }
}
