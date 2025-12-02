<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Get all department database connections.
     *
     * @return array<string>
     */
    private function getConnections(): array
    {
        return [
            'technical_department',
            'account_department',
            'product_department',
            'general_department',
            'feedback_department',
        ];
    }

    /**
     * Run the migrations across all department databases.
     */
    public function up(): void
    {
        foreach ($this->getConnections() as $connection) {
            Schema::connection($connection)->create('tickets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('subject');
                $table->text('description');
                $table->string('type');
                $table->string('status')->default('open');
                $table->timestamps();

                $table->index('status');
                $table->index('type');
                $table->index('created_at');
                $table->index(['status', 'created_at']);
            });

            Schema::connection($connection)->create('ticket_notes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->text('note');
                $table->timestamps();

                $table->foreign('ticket_id')
                    ->references('id')
                    ->on('tickets')
                    ->onDelete('cascade');

                $table->index('ticket_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->getConnections() as $connection) {
            Schema::connection($connection)->dropIfExists('ticket_notes');
            Schema::connection($connection)->dropIfExists('tickets');
        }
    }
};
