<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
use App\Models\orderItemModel;
use App\Models\orderModel;
use App\Models\paymentModel;
use App\Models\productModel;
use App\Models\transaction;
use App\Models\transactionItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
class orderController extends Controller
{
    public function create(Request $request) {
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_ISPRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('IS3DS');
        //1.Check if user still login
        $user = Auth::user();


        if ($user === null) {
            return ResponseFormatter::error('Please Login for continue ', 401);
        }
        // Get payment code
        //Get Key
        // $key =base64_decode(env('MIDTRANS_SERVER_KEY'));

        $transaction_details = array(
            'order_id'    => time(),
            'gross_amount'  => $request->amount
        );

        $name = explode(' ', $user['name']);
        if (count($name) > 1) {
            $customer_details = array(
                'first_name'       => $name[0],
                'last_name'        => $name[1],
                'email'            => $user['email'],
                'phone'            => "08214124",

            );
        } else {
            $customer_details = array(
                'first_name'       => $name[0],
                'email'            => $user['email'],
                'phone'            => "08124124",

            );
        }

        $itemTotal = [];
        foreach ($request->item_list as $itemList) {
            $itemTotal[] =  array(
                'id' => $itemList['product']['id'],
                'price' => $itemList['product']['price'],
                'quantity' => $itemList['quantity'],
                'name' =>  $itemList['product']['name'],
            );
        };

        $echannel = array(
            "bill_info1" => "Payment:",
            "bill_info2" => "Pembelian di freshmarket"
        );
        $transaction_data = array(
                "payment_type" => "echannel",
            'transaction_details' => $transaction_details,
            'echannel' => $echannel,
            'item_details' => $itemTotal,
            'customer_details' => $customer_details

        );

        $response = \Midtrans\CoreApi::charge($transaction_data);
        //Add to payment
        $expire = Carbon::now('Asia/Jakarta')->addDays(1)->timestamp;
        $dateString = Carbon::now('Asia/Jakarta')->addDays(1);
        $payment = paymentModel::create([
            'users_id' => $user['id'],
            'midtrans_order_id' => $response->order_id,
            'amount' => $response->gross_amount,
            'payment_url' => $response->transaction_id,
            'expire_time_unix' => $expire,
            'expire_time_str' => $dateString,
            'payment_status' => 2,
            'snap_url' => '-',
            'service_name' => "Mandiri",
            'payment_code' => $response->biller_code,
            'payment_key' => $response->bill_key,
        ]);
        $order = orderModel::create([
            'amount' => $request->amount,
            'shipping_amount' => 0,

            'payments_id' => $payment['id']
        ]);
        foreach($request->item_list as $itemList) {
            orderItemModel::create([
                     'order_id' => $order->id,
                    'quantity' => $itemList['quantity'],
                    'product_id' => $itemList['product']['id'],
                    'amount' => $itemList['product']['price'] * $itemList['quantity'],
            ]);



        }
        return ResponseFormatter::success($response, 'berhasil');
    }
    public function bca(Request $request ){
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_ISPRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('IS3DS');
        //1.Check if user still login
        $user = Auth::user();


        if ($user === null) {
            return ResponseFormatter::error('Please Login for continue ', 401);
        }
        // Get payment code
        //Get Key
        // $key =base64_decode(env('MIDTRANS_SERVER_KEY'));

        $transaction_details = array(
            'order_id'    => time(),
            'gross_amount'  => $request->amount
        );

        $name = explode(' ', $user['name']);
        if (count($name) > 1) {
            $customer_details = array(
                'first_name'       => $name[0],
                'last_name'        => $name[1],
                'email'            => $user['email'],
                'phone'            => "08214124",

            );
        } else {
            $customer_details = array(
                'first_name'       => $name[0],
                'email'            => $user['email'],
                'phone'            => "08124124",

            );
        }

        $itemTotal = [];
        $totalPrice = 0;
        foreach ($request->item_list as $itemList) {
            $itemTotal[] =  array(
                'id' => $itemList['product']['id'],
                'price' => $itemList['product']['price'],
                'quantity' => $itemList['quantity'],
                'name' =>  $itemList['product']['name'],
            );
            $totalPrice += $itemList['product']['price'] * $itemList['quantity'];
        };

        if ($request->promo != null) {
            $promo = array(
                'id' => $request->promo['id'],
                'price' => - ($totalPrice * $request->promo['discount_percetange'] / 100),
                'quantity' => 1,
                'name' =>
                $request->promo['voucher_description'],
            );
            array_push($itemTotal, $promo);
        } else {
            $promo = array(
                'id' => 0,
                'price' => 0,
                'quantity' => 1,
                'name' => 'no promo'

            );
            array_push($itemTotal, $promo);
        }

        $bank_transfer = array(
            "bank" => "bca"
        );
        $transaction_data = array(
            'payment_type' => 'bank_transfer',
            'transaction_details' => $transaction_details,
            'bank_transfer' => $bank_transfer,
                'item_details' => $itemTotal,
            'customer_details' => $customer_details

        );

        $response = \Midtrans\CoreApi::charge($transaction_data);
        //Add to payment
        $expire = Carbon::now('Asia/Jakarta')->addDays(1)->timestamp;
        $dateString = Carbon::now('Asia/Jakarta')->addDays(1);
        $payment = paymentModel::create([
            'users_id' => $user['id'],
            'midtrans_order_id' => $response->order_id,
            'amount' => $response->gross_amount,
            'payment_url' => $response->transaction_id,
            'expire_time_unix' => $expire,
            'expire_time_str' => $dateString,
            'payment_status' => 2,
            'snap_url' => '-',
            'service_name' => "BCA virtual Account",
            'delivery_type' => $request->deliveryType,
             'payment_key' => $response->va_numbers[0]->va_number,
        ]);
        // $payment = paymentModel::create([
        //     'users_id' => $user['id'],
        //     'midtrans_order_id' => $response->order_id,
        //     'amount' => $response->gross_amount,
        //     'payment_url' => $response->transaction_id,
        //     'expire_time_unix' => $expire,
        //     'expire_time_str' => $dateString,
        //     'payment_status' => 2,
        //     'snap_url' => '-',
        //     'service_name' => "BCA virtual Account",
        //     'delivery_type' => $request->delivery_type,
        //     ,'payment_key' => $response->va_numbers[0]->va_number,
        // ]);
        $order = orderModel::create([
            'amount' => $request->amount,
            'shipping_amount' => 0,

            'payments_id' => $payment['id']
        ]);
        foreach ($request->item_list as $itemList) {
            orderItemModel::create([
                'order_id' => $order->id,
                'quantity' => $itemList['quantity'],
                'product_id' => $itemList['product']['id'],
                'amount' => $itemList['product']['price'] * $itemList['quantity'],
            ]);
        }
        return ResponseFormatter::success($response, 'berhasil');
    }
    public function indomaret(Request $request ){
        \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_ISPRODUCTION');
        \Midtrans\Config::$is3ds = (bool) env('IS3DS');
        //1.Check if user still login
        $user = Auth::user();


        if ($user === null) {
            return ResponseFormatter::error('Please Login for continue ', 401);
        }
        // Get payment code
        //Get Key
        // $key =base64_decode(env('MIDTRANS_SERVER_KEY'));

        $transaction_details = array(
            'order_id'    => time(),
            'gross_amount'  => $request->amount
        );

        $name = explode(' ', $user['name']);
        if (count($name) > 1) {
            $customer_details = array(
                'first_name'       => $name[0],
                'last_name'        => $name[1],
                'email'            => $user['email'],
                'phone'            => "08214124",

            );
        } else {
            $customer_details = array(
                'first_name'       => $name[0],
                'email'            => $user['email'],
                'phone'            => "08124124",

            );
        }

        $itemTotal = [];
        $totalPrice = 0;
        foreach ($request->item_list as $itemList) {
            $itemTotal[] =  array(
                'id' => $itemList['product']['id'],
                'price' => $itemList['product']['price'],
                'quantity' => $itemList['quantity'],
                'name' =>  $itemList['product']['name'],
            );
            $totalPrice += $itemList['product']['price'] * $itemList['quantity'];

        };

        if($request->promo != null) {
            $promo = array(
                'id' => $request->promo['id'],
                'price' => - ($totalPrice * $request->promo['discount_percetange'] / 100),
                'quantity' => 1,
                'name' =>
                $request->promo['voucher_description'],
            );
            array_push($itemTotal, $promo);
        }else{
            $promo = array(
                'id' =>0,
                'price' => 0,
                'quantity' => 1,
                'name' => 'no promo'

            );
            array_push($itemTotal, $promo);
        }
        $conv_store = array(
            "store" => "indomaret",
            "message" => "pembayaran freshmart"
        );
        $transaction_data = array(
            'payment_type' => 'cstore',
            'transaction_details' => $transaction_details,
            'cstore' => $conv_store,
             'item_details' => $itemTotal,
            'customer_details' => $customer_details

        );
        $response = \Midtrans\CoreApi::charge($transaction_data);
        //Add to payment
        $expire = Carbon::now('Asia/Jakarta')->addDays(1)->timestamp;
        $dateString = Carbon::now('Asia/Jakarta')->addDays(1);
        $payment = paymentModel::create([
            'users_id' => $user['id'],
            'midtrans_order_id' => $response->order_id,
            'amount' => $response->gross_amount,
            'payment_url' => $response->transaction_id,
            'expire_time_unix' => $expire,
            'expire_time_str' => $dateString,
            'payment_status' => 2,
            'snap_url' => '-',
            'service_name' => "Indomaret"
            ,'payment_key' => $response->payment_code,
        ]);
        $order = orderModel::create([
            'amount' => $request->amount,
            'shipping_amount' => 0,

            'payments_id' => $payment['id']
        ]);
        foreach ($request->item_list as $itemList) {
            orderItemModel::create([
                'order_id' => $order->id,
                'quantity' => $itemList['quantity'],
                'product_id' => $itemList['product']['id'],
                'amount' => $itemList['product']['price'] * $itemList['quantity'],
            ]);
        }
        return ResponseFormatter::success($response, 'berhasil');
    }
    public function webHookHandler(Request $request) {
        $data = $request->all();

        $signatureKey = $data['signature_key'];
        $orderId = $data['order_id'];
        $statusCode = $data['status_code'];
        $grossAmount = $data['gross_amount'];
        $serverKey = env('MIDTRANS_SERVER_KEY');


        $mySignatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $transactioStatus = $data['transaction_status'];
        $type = $data['payment_type'];
        $fraudStatus = $data['fraud_status'];


        if ($signatureKey !== $mySignatureKey) {
            return ResponseFormatter::error('invalid signature', 400);
        };
        $payment = paymentModel::where('midtrans_order_id', $orderId)->first();
        $order = orderModel::where('payments_id', $payment->id)->with('orderItem')->get();
        // $buyers = buyer::where('id',$payment->buyers_id);

        if ($payment->payment_status === 1) {
            return ResponseFormatter::error('Operation not permitted', 405);
        }

        if ($transactioStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                // TODO set transaction status on your database to 'challenge'
                $payment->payment_status = 3;
                $payment->payment_status_str = 'challenge';

                // and response with 200 OK
            } else if ($fraudStatus == 'accept') {
                // TODO set transaction status on your database to 'success'
                // and response with 200 OK
                $payment->payment_status = 1;
                $payment->payment_status_str = 'settlement';
            }
        } else if ($transactioStatus == 'settlement') {
            // TODO set transaction status on your database to 'success'

            $buyers = User::where('id', $payment->users_id)->first();
            foreach ($order as $o) {
                $transction = transaction::create([
                    "invoice" => "INV" . "/" . $o->id,
                    "amount" => $o->amount,
                    "shipping_amount" => $o->shipping_amount,
                    "users_id" => $buyers->id,
                    "payment_id" => $payment->id,
                    'status' => 2,
                    'status_str' => 'DIPROSES',
                    'delivery_type' => $payment->delivery_type
                ]);

                foreach ($o['orderItem'] as $item) {


                    transactionItem::create([
                        'transaction_id' => $transction->id,
                        'quantity' => $item->quantity,
                        'amount' => $item->amount,
                        'product_id' => $item->product_id,
                    ]);
                }

                // if ($buyers['username_lkpp'] !== null) {


                //         Http::withHeaders(['X-Client-Id' => env('X_Client_Id'), 'X-Client-Secret' => env('X_Client_Secret')])->post(env('TOKODARING_TRANSACTION_UPDATE'), [
                //         'order_id' => "INV" . "/" . $o->id,
                //         'konfirmasi_ppmse' => true,
                //         'token' => $buyers['token_transaction_lkpp']

                //     ]);



                // }

            };

            $payment->payment_status = 1;
            $payment->payment_status_str = 'settlement';



            // and response with 200 OK
        } else if (
            $transactioStatus == 'cancel' ||
            $transactioStatus == 'deny' ||
            $transactioStatus == 'expire'
        ) {
            // TODO set transaction status on your database to 'failure'
            $payment->payment_status = 3;
            $payment->payment_status_str = 'cancel';






            // and response with 200 OK
        } else if ($transactioStatus == 'pending') {
            // TODO set transaction status on your database to 'pending' / waiting payment
            $payment->payment_status = 2;
            $payment->payment_status_str = 'pending';

            // foreach ($order as $o) {
            //     $value = $order['amount'] + $order['shipping_amount'];
            //     $email = $user

            //     foreach ($o['orderItem'] as $item) {

            //     }
            // };



            // and response with 200 OK
        }
        $payment->save();
        $logData = [
            'payment_status_str' => $transactioStatus,
            'payment_status_int' => $payment->payment_status,
            'payments_id' => $payment->id,
            'payment_type' => $type,
            'raw_response' => json_encode($data)

        ];
        return true;
    }


    public function paymentCode(Request $request) {
        $transactionId = $request->query("transaction_id");

        $payment = paymentModel::where('payment_url', $transactionId)->select('amount', 'expire_time_unix', 'expire_time_str', 'service_name', 'payment_status', 'payment_key', 'payment_code')->first();

        return ResponseFormatter::success($payment);


    }

    public function getTransaction(){
        $user = Auth::user();

        if ($user == null) {
            return ResponseFormatter::error("Please login for add address");
        }


        $transaction = transaction::with('transactionItem.product')->where("users_id",$user['id'])->select('id','invoice','amount','delivery_type','status')->get();

        return ResponseFormatter::success($transaction);
    }

    public function editTransaction(Request $request)
    {


        transaction::where('invoice', $request->invoice)
            ->update([
                 'status' => $request->status
            ]);


        return ResponseFormatter::success(1,'success');
    }
}
