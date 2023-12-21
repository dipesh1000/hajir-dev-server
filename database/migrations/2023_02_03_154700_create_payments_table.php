<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['Paid','Unpaid', 'Hold'])->default('Unpaid');
            $table->unsignedDouble('paid_amount')->nullable();
            $table->unsignedDouble('bonus')->nullable();
            $table->unsignedDouble('deduction')->nullable();
            $table->enum('allowance_type', ["Daily", "Monthly"]);
            $table->date('payment_date')->nullable();
            $table->date('payment_for_month')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('employer_id')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('candidate_id')->references('id')->on('users');
            $table->foreign('employer_id')->references('id')->on('users');

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
        Schema::dropIfExists('payments');
    }
}
