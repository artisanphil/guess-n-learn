<?php

use App\Constants\QuestionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_answers', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                QuestionType::MCHOICE,
                QuestionType::GAP,
                QuestionTYPE::DRAGDROP
            ]);
            $table->string('attribute', 30);
            $table->string('input', 100);
            $table->boolean('correct');
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
        Schema::dropIfExists('log_answers');
    }
}
