<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // $table->unsignedBigInteger('company_id')->nullable();
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // $table->unsignedBigInteger('candidate_id')->nullable();
            // $table->foreign('candidate_id')->references('id')->on('users')->onDelete('cascade');

            // $table->unsignedBigInteger('employer_id')->nullable();
            // $table->foreign('employer_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
