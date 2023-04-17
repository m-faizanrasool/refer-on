<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->index();
            $table->string('key')->unique()->index();
            $table->unsignedSmallInteger('brand_id')->index();
            $table->unsignedSmallInteger('country_id')->index();
            $table->unsignedBigInteger('submitter_id')->index();
            $table->unsignedBigInteger('executor_id')->index()->nullable();
            $table->string('website');
            $table->text('summary');
            $table->integer('submitter_credits');
            $table->integer('executor_credits')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->enum('status', ['VERIFIED', 'DISPUTED', 'PENDING_VERIFICATION', 'INVALID', 'AVAILABLE'])->default('AVAILABLE');
            $table->json('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('submitter_id')->references('id')->on('users');
            $table->foreign('executor_id')->references('id')->on('users');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
