<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvitationIdToCompanyCandidates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('invitation_id')->nullable()->after('company_id');
            $table->foreign('invitation_id')->references('id')->on('invitations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_candidates', function (Blueprint $table) {
            $table->dropForeign('invitation_id');
            $table->dropColumn('invitation_id');
        });
    }
}
