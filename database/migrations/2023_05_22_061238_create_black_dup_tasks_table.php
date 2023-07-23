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
        Schema::create('black_dup_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('tasks');
            $table->string('code')->index();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('submitter_id')->constrained('users');
            $table->enum('status', ['BLACKLISTED', 'DUPLICATE_CODE']);
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
        Schema::dropIfExists('black_dup_tasks');
    }
};
