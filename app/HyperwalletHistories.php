<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HyperwalletHistories extends Model
{

    use SoftDeletes;

    protected $fillable = ['user_id','request','response','operation','status'] ;


    public static function connect($action, $request, $user_id)
    {

        $url = (env('HYPERWALLET_MODE') == 'test') ? 'https://uat-api.paylution.com/rest/v3/'.$action : 'https://api.paylution.com/rest/v3/'.$action ;
        $username = env('HYPERWALLET_USERNAME');
        $password = env('HYPERWALLET_PASSWORD');
        $issuerId = env('HYPERWALLET_ISSUER_ID');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $curl_result = curl_exec($curl);
        curl_close($curl);
        $return_data = $curl_result;
        // die();

        self::create([
            'user_id'=>$user_id,
            'request'=>$request,
            'response'=>$return_data,
            'operation'=>$action,
        ]);
        return $return_data;

    }
    public static function createaccount($user, $user_id)
    {


        //     dd($user['firstname']);
        //    echo $user['zone_code'];
        $data = [
            "addressLine1"=> substr($user['address'], 0, 99),
            "city"=> $user['city'],
            "clientUserId"=> $user['username'],
            "country"=> $user['country'],
            "dateOfBirth"=> date('Y-m-d', strtotime($user['dob'])),
            "email"=> $user['email'],
            "firstName"=> $user['firstname'],
            "lastName"=> $user['lastname'],
            "postalCode"=> $user['postcode'],
            "mobileNumber"=> $user['phone'],
            "gender"=> "MALE",
            "profileType"=> "INDIVIDUAL",
            "programToken"=>  env('HYPERWALLET_TOKEN'),
            "stateProvince"=> $user['state']
        ];

        // $data = array(
        //     "addressLine1"=> 'test address',
        //     "city"=> 'test city',
        //     "clientUserId"=> 'test_username2',
        //     "country"=> 'us',
        //     "dateOfBirth"=> "1991-01-01",
        //     "email"=> 'testemail5@gmail.com',
        //     "firstName"=> 'test name',
        //     "lastName"=> 'test lastname',
        //     "postalCode"=> '94105',
        //     "mobileNumber"=> '+17854126536',
        //     "gender"=> "MALE",
        //     "profileType"=> "INDIVIDUAL",
        //     "programToken"=>  env('HYPERWALLET_TOKEN'),
        //     "stateProvince"=> 'CA'
        // );
        $data = json_encode($data);

        $responseCode = self::connect('users', $data, $user_id);
        $response = json_decode($responseCode,true);

        if(!isset($response['errors']))
        {
            User::where('id',$user_id)->update(['sort_code'=>$response['clientUserId'], 'hw_token'=>$response['token']]);
        }

        return($response);

    }
    public static function transfer($user_id, $amount, $payoutid)
    {
        $amount = number_format((float)$amount, 2, '.', '');
        $hyperwallet_token = User::where('id', $user_id)->value('hw_token');
        // $hyperwallet_token = "usr-8900ac8a-5c15-11e5-8009-0050569ad8cb";

        if($hyperwallet_token != null)
        {
            // $merchant_tax_id = 'aoji'.time();
            $clientPaymentId = 'txn_'.time();
            $request_id = $payoutid;

            $data = [
                "amount"=> $amount,
                "clientPaymentId"=> $clientPaymentId,
                "currency"=> "USD",
                "destinationToken"=> $hyperwallet_token,
                "programToken"=> env('HYPERWALLET_TOKEN'),
                "purpose"=> "OTHER",
                "notes"=> "Request".$request_id,
            ];

            //  $payout_xml = '<transaction>
            //                     <directLoad>
            //                         <account>
            //                             <extraId>' . $Hyperwalletid . '</extraId>
            //                         </account>
            //                         <amount>$' . $amount . '</amount>
            //                         <currencyCode>USD</currencyCode>
            //                         <merchantTxnId>' . $merchant_tax_id . '</merchantTxnId>
            //                         <notes>Request . ' . $request_id . '</notes>
            //                         <description>Payroll  ' . date('d M Y') . ' - $' . $amount . ' load</description>
            //                     </directLoad>
            //                 </transaction>';

            // echo $payout_xml;
            $data = json_encode($data);
            $response = self::connect('payments', $data, $user_id);
            //$response = json_decode($response,true);

            return $response;
            /*
           */
        }
    }

    public static function checkaccount($extraId)
    {
        $userToken = User::where('sort_code', $extraId)->value('hw_token');
        // $userToken = "usr-8900ac8a-5c15-11e5-8009-0050569ad8cb";
        if($userToken != null)
        {
            $username = env('HYPERWALLET_USERNAME');
            $password = env('HYPERWALLET_PASSWORD');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.paylution.com/rest/v3/users/'.$userToken);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Accept: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $curl_result = curl_exec($ch);
            if (curl_errno($ch))
            {
                echo 'Error:' . curl_error($ch);
            }
            curl_close ($ch);
            $response = json_decode($curl_result, true);
            if($response['status'] == 'ACTIVATED' || $response['status'] == 'PRE_ACTIVATED')
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
