<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNotesTableReplaceCategoryWithCategoryId extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'category')) {
                $table->dropColumn('category');
            }

            if (!Schema::hasColumn('notes', 'category_id')) {
                $table->unsignedBigInteger('category_id')->after('text');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }

            if (!Schema::hasColumn('notes', 'category')) {
                $table->string('category')->after('text');
            }
        });
    }
}
