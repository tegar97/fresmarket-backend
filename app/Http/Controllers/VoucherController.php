<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\user_voucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Auth;

class VoucherController extends Controller

{

    public function index()
    {
        $voucher = Voucher::all();

        return  View('pages.voucher.index', compact('voucher'));
    }
    public function create(Request $request) {

        $voucher = Voucher::where('code', $request->code)->first();
        if ($voucher != null) {
            return ResponseFormatter::error("Voucher already exist");
        }
        $voucher = Voucher::create([
            'code' => $request->code,
            'max_discount' => $request->max_discount,
            'min_order' => $request->min_order,
            'max_use' => $request->max_use,
            'use_count' =>0,
            'voucher_type' => $request->voucher_type,
            'voucher_description' => $request->voucher_description,
            'discount_percetange' => $request->discount_percetange,
            'expired_at' => date('Y-m-d', strtotime($request->expired_at)),
        ]);

        return ResponseFormatter::success($voucher);
    }

    public function claimVoucher(Request $request){
        $user = Auth::user();

        $voucher = Voucher::where('code', $request->code)->first();
        if ($user == null) {
            return ResponseFormatter::error("Please login to claim voucher",'Silahkan login terlebih dahulu untuk klaim voucher');
        }
        if ($voucher == null) {
            return ResponseFormatter::error("Voucher not found",'voucher tidak ditemukan');
        }
        if ($voucher->expired_at < Date('Y-m-d H:i:s')) {
            return ResponseFormatter::error("Voucher expired",'Voucher telah expired');
        }

        $user_voucher = user_voucher::where('users_id', $user->id)->where('voucher_id', $voucher->id)->first();
        if ($user_voucher != null) {
            return ResponseFormatter::error(null, 'Voucher telah di klaim');
        }

        if($voucher->use_count < $voucher->max_use){
            $voucher->use_count = $voucher->use_count + 1;
            $voucher->save();

            $user_voucher = new user_voucher();
            $user_voucher->users_id = $user->id;
            $user_voucher->voucher_id = $voucher->id;
            $user_voucher->is_used = false;
            $user_voucher->save();
            return ResponseFormatter::success($voucher);
        }else{
            return ResponseFormatter::error(null, 'Voucher sudah tidak bisa digunakan', 500);
        }
    }

    public function getAvailableVoucher() {
        $voucher = Voucher::where('expired_at', '>', Date('Y-m-d H:i:s'))->get();
        return ResponseFormatter::success($voucher);
    }

    public function getMyVoucher(){
        $user = Auth::user();
        if($user == null){
            return ResponseFormatter::error("Please login to claim voucher",'Silahkan login terlebih dahulu untuk klaim voucher');
        }
        $voucher = user_voucher::with('voucher')->where('users_id', $user->id)->get();

        $newVoucher = [];
        for ($i=0; $i < count($voucher); $i++) {
            $voucherField = $voucher[$i]->voucher;
            $merge = array_merge($voucher[$i]->toArray(), $voucherField->toArray());
            unset($merge['voucher']);
            $newVoucher[] = $merge;
        }

        return ResponseFormatter::success($newVoucher);
    }

    public function checkValidVoucher(Request $request) {
        $user = Auth::user();
        if($user == null){
            return ResponseFormatter::error("Please login to claim voucher",'Silahkan login terlebih dahulu untuk klaim voucher');
        }
        $checkVoucher = user_voucher::where('users_id', $user->id)->where('voucher_id', $request->voucher_id)->where('is_used',false)->first();

        if($checkVoucher == null){
            return ResponseFormatter::error(null, 'Voucher tidak valid atau telah digunakan', 500);
        }

        $voucher = Voucher::where('id', $request->voucher_id)->first();
        if($voucher->expired_at < Date('Y-m-d H:i:s')){
            return ResponseFormatter::error(null, 'Voucher sudah tidak bisa digunakan', 500);
        }


       return ResponseFormatter::success($voucher);


    }

    public function useVoucher(Request $request) {
        $user = Auth::user();
        if($user == null){
            return ResponseFormatter::error("Please login to claim voucher",'Silahkan login terlebih dahulu untuk klaim voucher');
        }
        $checkVoucher = user_voucher::where('users_id', $user->id)->where('voucher_id', $request->voucher_id)->where('is_used',false)->first();

        if($checkVoucher == null){
            return ResponseFormatter::error(null, 'Voucher tidak valid atau telah digunakan', 500);
        }

        $voucher = Voucher::where('id', $request->voucher_id)->first();
        if($voucher->expired_at < Date('Y-m-d H:i:s')){
            return ResponseFormatter::error(null, 'Voucher sudah tidak bisa digunakan', 500);
        }

        $checkVoucher->is_used = true;
        $checkVoucher->save();

        return ResponseFormatter::success($voucher);
    }
}
