<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->float('discount_amount');
            $table->float('radius');
            // can add as many units but for now lets use km and metres
            $table->enum('radius_unit', ['meter', 'km'])->default('km');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_used')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps(); // created_at & updated_at fields
            $table->softDeletes(); // support for soft deletes on the table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promocodes');
    }
}
