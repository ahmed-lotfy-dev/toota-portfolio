<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userExist = User::where('email', 'elsheanawy19@gmail.com')->first();
        if (!$userExist) {
            User::create([
                'name' => 'Admin User',
                'email' => 'elshenawy19@gmail.com',
                'password' => env('DEFAULT_ADMIN_PASSWORD'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'elshenawy19@gmail.com')->delete();
    }
};
