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
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('tasks');
            $table->foreignId('sibling_id')->nullable()->constrained('tasks');
            $table->string('code')->index();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('submitter_id')->constrained('users');
            $table->foreignId('executor_id')->nullable()->constrained('users');
            $table->timestamp('fulfilled_at')->nullable();
            $table->enum('status', ['VERIFIED', 'DISPUTED', 'PENDING_VERIFICATION', 'INVALID', 'AVAILABLE'])->default('AVAILABLE');
            $table->json('tags')->nullable();
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
        Schema::dropIfExists('tasks');
    }
}
