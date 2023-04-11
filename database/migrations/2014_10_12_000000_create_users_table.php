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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_admin')->default(false);
            $table->string('name');
            $table->string('email')->unique()->index();
            $table->string('phone')->unique()->index();
            $table->unsignedSmallInteger('country_id')->index()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedSmallInteger('demerit_points')->nullable()->index();
            $table->timestamp('blocked_until')->nullable();
            $table->enum('status', ['ACTIVE', 'BLOCKED', 'PERMANENTLY_BLOCKED'])->default('ACTIVE');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('users');
    }
};
