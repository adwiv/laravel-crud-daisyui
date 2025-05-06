<?php

use App\Degree;
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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 10);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->enum('degree', ['graduate', 'postgrad', 'doctorate']);
            $table->enum('likes', ['reading', 'writing', 'drawing', 'cooking', 'dancing', 'singing', 'other']);
            $table->string('address', 1024);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
