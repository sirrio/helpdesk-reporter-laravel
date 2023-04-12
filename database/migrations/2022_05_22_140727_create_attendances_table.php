<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('semester');
            $table->date('date');
            $table->time('startTime');
            $table->time('endTime');
            $table->string('degree');
            $table->string('faculty');
            $table->boolean('mathBasic');
            $table->boolean('mathLow');
            $table->boolean('mathHigh');
            $table->boolean('programming');
            $table->boolean('physics');
            $table->boolean('chemistry');
            $table->boolean('organization');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
