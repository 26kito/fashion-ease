<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->string('image')->after('description')->nullable();
            $table->integer('price')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('products', function (Blueprint $table) {
        //     $table->renameColumn('nama', 'name');
        //     $table->renameColumn('keterangan', 'description');
        //     $table->text('image')->after('keterangan');
        // });
        Schema::dropIfExists('products');
    }
}