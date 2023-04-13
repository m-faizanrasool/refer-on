<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklisted_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->unsignedSmallInteger('country_id')->index();
            $table->unsignedSmallInteger('brand_id')->index();
            $table->string('source')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('blacklisted_tasks');
    }
};
