<?php

use App\Enums\EmploymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        Schema::create('employees', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->foreignId('department_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('position_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('family_name');
            $table->string('given_name');
            $table->string('family_name_kana');
            $table->string('given_name_kana');
            $table->string('email')->unique();

            if ($driver === 'sqlite') {
                $table->enum('employment_status', EmploymentStatus::cases())
                    ->default(EmploymentStatus::Active->value);
            } else {
                $table->string('employment_status')->default(EmploymentStatus::Active->value);
            }

            $table->timestamps();
        });

        if ($driver === 'mysql') {
            $allowedStatuses = implode(', ', array_map(
                fn (EmploymentStatus $status): string => DB::getPdo()->quote($status->value),
                EmploymentStatus::cases()
            ));

            DB::statement(
                "ALTER TABLE employees
                ADD CONSTRAINT employees_employment_status_check
                CHECK (employment_status IN ({$allowedStatuses}))"
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
