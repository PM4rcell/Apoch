<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('era_id')->constrained("eras");
            $table->foreignId('director_id')->constrained('directors');
            $table->string('title');
            $table->text('description');
            $table->float('vote_avg');
            $table->integer('imdb_id');
            $table->string("omdb_category");
            $table->string('age_rating');
            $table->date('release_date');
            $table->integer('runtime_min');
            $table->boolean('is_featured')->default(false);
            $table->string('slug')->unique();
            $table->string('trailer_link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
