 <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->unsignedBigInteger('leave_id')->nullable();
            $table->enum('employee_status', ['Present', 'Absent', 'Leave', 'Late'])->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedDouble('earning')->nullable();

            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('set null');
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('set null');
            $table->foreign('candidate_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->float('overtime_earning')->nullable();
        

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
        Schema::dropIfExists('attendances');
    }
}
