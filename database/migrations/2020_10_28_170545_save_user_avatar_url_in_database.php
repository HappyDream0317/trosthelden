<?php

use App\User;
use Illuminate\Database\Migrations\Migration;

class SaveUserAvatarUrlInDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        User::all()->each(function (User $user) {
            $fileName = 'uploads/'. $user->nickname.'.png';
            if (Storage::disk('public')->has($fileName)) {
                $user->avatar = $fileName;
                $user->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
