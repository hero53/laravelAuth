<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    //

    public function redirect(Provider $provider)
    {
           return Socialite::driver($provider->value)->redirect();
    }

    public function callback(Provider $provider)
    {

            try {
                $userSocial = Socialite::driver($provider->value)->user();
              //  dd($userSocial->getName());

                $user = User::firstOrCreate(
                    [
                        'email'=>strtolower($userSocial->getEmail()),
                        'name'=>$userSocial->getName(),
                        'password'=>Hash::make('1111111111'),
                    ]
                );
                if ($user) {
                    Auth::login($user);
                    // Redirigez l'utilisateur vers la route 'dashboard'
                    return redirect()->route('dashboard');
                } else {
                    // Gérez le cas où l'utilisateur n'existe pas dans votre application
                    return redirect()->route('login')->with('error', 'L\'utilisateur n\'existe pas.');
                }
            } catch (\Throwable $th) {
                //throw $th;
                dd($th);
            }
    }
}

