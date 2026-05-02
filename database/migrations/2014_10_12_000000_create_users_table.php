<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile')->unique()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('avatar')->nullable();
            $table->enum('role', ['worker', 'employer','admin'])->default('worker');
            $table->boolean('is_admin')->default(false);
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
        User::create(['id' => (string) Str::uuid(), 'first_name' => 'ادمین', 'last_name' => 'سیستم', 'email' => 'admin@test.com', 'mobile' => '09120000000', 'password' => Hash::make('12345678'),'email_verified_at'=>'2022-01-02 17:04:58','avatar' => 'avatar-1.jpg','created_at' => now(),'role' => 'admin','is_admin' => true]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
