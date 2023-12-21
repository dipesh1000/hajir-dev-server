<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_files', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('path')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->string('extension', 10)->nullable();
            $table->string('type', 15)->nullable();
            $table->timestamps();

            // $table->foreign('user_id')->
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
    }
}
