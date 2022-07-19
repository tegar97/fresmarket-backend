<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\userAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class userAddressController extends Controller
{
    public function create(Request $request) {
        $user = Auth::user();

        if($user == null) {
            return ResponseFormatter::error("Please login for add address");
        }
        $validator = Validator::make($request->all(), [
            'fullAddress' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'districts' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'max:255'],
        
        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $validator->errors(),
                ],
                401
            );
        }


        $oldAddress = userAddress::where("isMainAddress",1)->where('users_id', $user['id'])->first();
        if($oldAddress != null && $request->isMainAddress == 1) {
            $oldAddress['isMainAddress'] = false;
            $oldAddress->save();
        }

        $address = userAddress::create([
            'users_id' => $user['id'],
            'label' => $request->label,
            'fullAddress' => $request->fullAddress,
            'province' => $request->province,
            'city' => $request->city,
            'districts' => $request->districts,
            'phoneNumber' => $request->phoneNumber,
            'isMainAddress' => $request->isMainAddress,
            'street' => $request->street,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ],);

        return ResponseFormatter::success($address, 200);
    }

    public function getMyAddress() {
        $user = Auth::user();

        if ($user == null) {
            return ResponseFormatter::error("Please login for add address");
        }


        $getAllAdress = userAddress::where('users_id',$user['id'])->get();


        return ResponseFormatter::success($getAllAdress,200);
    }
    public function getMainAddress() {
        $user = Auth::user();

        if ($user == null) {
            return ResponseFormatter::error("Please login for add address");
        }


        $getAllAdress = userAddress::where('users_id',$user['id'])->where('isMainAddress',1)->first();


        return ResponseFormatter::success($getAllAdress,200);
    }
    public function ChangeMainAddress(Request $request) {
        $user = Auth::user();

        if ($user == null) {
            return ResponseFormatter::error("Please login for add address");
        }


        $oldAddress = userAddress::where('isMainAddress', 1)->first();
        $oldAddress['isMainAddress'] = 0;
        $oldAddress->save();
        $newAddress = userAddress::where('id',$request->newAddress)->first();
        $newAddress['isMainAddress'] = 1;
        $newAddress->save();

        return ResponseFormatter::success($newAddress,200);
    }
}
