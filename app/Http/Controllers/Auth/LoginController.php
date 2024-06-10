<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    // /*
    // |--------------------------------------------------------------------------
    // | Login Controller
    // |--------------------------------------------------------------------------
    // |
    // | This controller handles authenticating users for the application and
    // | redirecting them to your home screen. The controller uses a trait
    // | to conveniently provide its functionality to your applications.
    // |
    // */

    use AuthenticatesUsers;

    // /**
    //  * Where to redirect users after login.
    //  *
    //  * @var string
    //  */
    protected $redirectTo = RouteServiceProvider::HOME;

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            return redirect()->route('checkout');
        }
    }

    public function logout(Request $request)
    {
        if (isset($_COOKIE['isVoucherUsed']) && isset($_COOKIE['selectedVouchersCode'])) {
            setcookie('isVoucherUsed', '', time() - 1, '/');
            setcookie('selectedVouchersCode', '', time() - 1, '/');
            setcookie('totalPriceCart', '', time() - 1, '/');
            setcookie('appliedDiscPrice', '', time() - 1, '/');
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('gauth_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);

                return redirect()->route('home');
            } else {
                $pass = generateRandomString();

                $newUser = User::create([
                    'first_name' => $user->name,
                    'last_name' => $user->given_name,
                    'email' => $user->email,
                    'role_id' => 2,
                    'gauth_id' => $user->id,
                    'gauth_type' => 'google',
                    'password' => Hash::make($pass)
                ]);

                Auth::login($newUser);

                return redirect()->route('home');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
