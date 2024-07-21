<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return redirect()->route('cart');
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

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();

            $finduser = DB::table('social_users')
                ->where('provider_id', $user->id)
                ->where('provider_name', $provider)
                ->first();

            if (!$finduser) {
                DB::beginTransaction();

                $pass = generateRandomString();

                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    ['first_name' => $user->name, 'role_id' => 2, 'password' => Hash::make($pass)]
                );

                DB::table('social_users')->insert([
                    'user_id' => $newUser->id,
                    'provider_id' => $user->id,
                    'provider_name' => $provider,
                    'provider_token' => $user->token,
                    'provider_refresh_token' => $user->refreshToken,
                ]);

                DB::commit();

                Auth::login($newUser);

                setcookie('isLogin', true, time() + (3600 * 2), '/');
                return;
                // return redirect()->route('home');
            }

            Auth::login(User::find($finduser->user_id));

            setcookie('isLogin', true, time() + (3600 * 2), '/');
            return;
            // return redirect()->route('home');
        } catch (Exception $e) {
            DB::rollback();

            dd($e->getMessage());
        }
    }
}
