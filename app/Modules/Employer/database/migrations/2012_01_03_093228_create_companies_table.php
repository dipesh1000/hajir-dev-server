<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('code')->default(1);
            $table->enum('status',['Active', 'Inactive'])->default('Active');
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('working_days')->nullable();
            $table->time('office_hour_start')->nullable();
            $table->time('office_hour_end')->nullable();
            $table->string('office_hour')->nullable();
            $table->ipAddress('network_ip')->nullable();
            $table->enum('calculation_type', ['calendar_days', '30_days'])->nullable();

            $table->enum('salary_type', ['monthly', 'weekly', 'daily'])->nullable();

            $table->enum('leave_duration_type', ['Yearly', 'Monthly'])->nullable();
            $table->unsignedBigInteger('leave_duration')->nullable();
            $table->unsignedBigInteger('probation_period')->nullable();

            $table->unsignedBigInteger('employer_id')->nullable();
            $table->foreign('employer_id')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
}
