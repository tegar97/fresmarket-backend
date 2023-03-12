<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\cartsModel;
use App\Models\productModel;
use App\Models\store;
use App\Models\User;
use App\Models\userAddress;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
function bubble_sort($arr)
{
    $size = count($arr) - 1;
    for ($i = 0; $i < $size; $i++) {
        for ($j = 0; $j < $size - $i; $j++) {
            $k = $j + 1;
            if ($arr[$k] < $arr[$j]) {
                // Swap elements at indices: $j, $k
                list($arr[$j], $arr[$k]) = array($arr[$k], $arr[$j]);
            }
        }
    }
    return $arr;
}
class UsersController extends Controller
{

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('appToken')->accessToken;
            return response()->json([
                'success' => true,
                'token' => $success,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ],
                401
            );
        }

        $carts = cartsModel::create([
            'total' => 0
        ]);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['avatar'] = 'avatar.png';
        $input['carts_id'] = $carts['id'];
        $user = User::create($input);
        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
            'success' => true,
            'token' => $success,
            'user' => $user
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Logout Success',
        ]);
    }

    public function getMe() {




        return ResponseFormatter::success(Auth::user(),200);
    }

    public function getNearOutlet() {


        $store = store::all();

        $user = Auth::user();

        $adress = userAddress::where('users_id',$user['id'])->where('isMainAddress',1)->first();
        $distance = [];
        foreach($store as $s) {

            $theta = $s['longitude'] - $adress['longitude'];

            $dist = sin(deg2rad($s['latitude'])) * sin(deg2rad($adress['latitude'])) +  cos(deg2rad($s['latitude'])) * cos(deg2rad($adress['latitude'])) * cos(deg2rad($theta));
            $dist =
                acos(min(max($dist, -1.0), 1.0));
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;


            $distance[] = array(
                'id' => $s['id'],
                'name' => $s['name'],
                'distance' => $miles * 1.609344
            );


        }
        $keys = array_column($distance, 'distance');
        array_multisort($keys, SORT_ASC, $distance);



        return ResponseFormatter::success($distance[0]);
    }
    public function getAllListOutlet() {


        $store = store::all();

        $user = Auth::user();

        $adress = userAddress::where('users_id',$user['id'])->where('isMainAddress',1)->first();
        $distance = [];
        foreach($store as $s) {

            $theta = $s['longitude'] - $adress['longitude'];

            $dist = sin(deg2rad($s['latitude'])) * sin(deg2rad($adress['latitude'])) +  cos(deg2rad($s['latitude'])) * cos(deg2rad($adress['latitude'])) * cos(deg2rad($theta));
            $dist =
                acos(min(max($dist, -1.0), 1.0));
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;


            $distance[] = array(
                'id' => $s['id'],
                'name' => $s['name'],
                'distance' => $miles * 1.609344
            );


        }
        $keys = array_column($distance, 'distance');
        array_multisort($keys, SORT_ASC, $distance);



        return ResponseFormatter::success($distance);
    }

}
