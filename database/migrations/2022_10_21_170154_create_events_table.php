<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->datetime('publish_at')->nullable()->index();
            $table->boolean('published')->default(false)->index();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('name')->nullable();
            $table->string('excerpt')->nullable();
            $table->json('header')->nullable();
            $table->json('content')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->datetime('publish_at')->nullable()->index();
            $table->boolean('published')->default(false)->index();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('event_type_id')->nullable()->index();
            $table->datetime('start_at')->nullable()->index();
            $table->datetime('end_at')->nullable()->index();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('name')->nullable();
            $table->string('excerpt')->nullable();
            $table->text('ticket_url')->nullable();
            $table->json('header')->nullable();
            $table->json('content')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->json('header')->nullable()->after('excerpt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['header']);
        });

        Schema::dropIfExists('events');
        Schema::dropIfExists('event_types');
    }
}
