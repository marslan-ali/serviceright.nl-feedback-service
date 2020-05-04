<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('branch');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('company_id');
            $table->timestamp('date');
            $table->tinyInteger('rating');
            $table->json('order_information');
            $table->timestamp('accepted')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('accepted');
            $table->index(['accepted', 'branch']);
            $table->index('branch');
            $table->index('order_id');
            $table->index('company_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
