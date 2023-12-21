<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->enum('type', ['Full', 'Half'])->default('Full');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->enum('pay_status', ['Paid', 'Unpaid'])->default('Paid');
            $table->foreign('document_id')->references('id')->on('upload_files')->onDelete('set null');
            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

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
        Schema::dropIfExists('leaves');
    }
}
