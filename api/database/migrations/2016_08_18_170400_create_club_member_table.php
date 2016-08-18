<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClubMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('club_member', function (Blueprint $table) {
            $table->integer('club_id')->unsigned();
            $table->foreign('club_id')
                ->references('id')->on('clubs')
                ->onDelete('cascade');

            $table->integer('member_id')->unsigned();
            $table->foreign('member_id')
                ->references('id')->on('members')
                ->onDelete('cascade');

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
        Schema::drop('club_member');
    }
}
