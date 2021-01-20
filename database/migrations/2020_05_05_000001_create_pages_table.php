<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Marshmallow\HelperFunctions\Traits\MigrationHelper;

class CreatePagesTable extends Migration
{
    use MigrationHelper;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
            });
        }

        $this->createColumnIfDoesntExist('pages', 'name', function (Blueprint $table) {
            $table->string('name')->after('id');
        });
        $this->createColumnIfDoesntExist('pages', 'slug', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
        });
        $this->createColumnIfDoesntExist('pages', 'layout', function (Blueprint $table) {
            $table->json('layout')->nullable()->default(null)->after('slug');
        });
        $this->createColumnIfDoesntExist('pages', 'created_at', function (Blueprint $table) {
            $table->timestamps();
        });
        $this->createColumnIfDoesntExist('pages', 'deleted_at', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('pages')) {
            Schema::dropIfExists('pages');
        }
    }
}
