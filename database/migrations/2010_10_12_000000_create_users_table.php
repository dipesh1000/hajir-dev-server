<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');



            $table->string('phone');
            $table->string('dob')->nullable();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->enum('type', ['employer', 'candidate', 'superadmin'])->nullable();
            $table->foreign('image_id')->references('id')->on('upload_files')->onDelete('set null');

            // $table->unsignedBigInteger('company_id')->nullable();
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
