<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class CreditCard extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'card_number', 'card_name', 'cvv', 'expiry_month', 'expiry_year', 'billing_address_id', 'payment_profile_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function billingAddress()
    {
        return $this->belongsTo('App\Address', 'billing_address_id');
    }

    public static function createAuthorizeCustomerProfile($credit_card, $billing_address, $user)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        $refId = 'ref' . time();

        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($credit_card->card_number);
        $creditCard->setExpirationDate($credit_card->expiry_year."-".$credit_card->expiry_month);
        $creditCard->setCardCode($credit_card->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($user->firstname);
        $billTo->setLastName($user->lastname);
        $billTo->setCompany('');
        $billTo->setAddress($billing_address->address_1.', '.$billing_address->address_2);
        $billTo->setCity($billing_address->city);
        $billTo->setState($billing_address->state->name);
        $billTo->setZip($billing_address->postcode);
        $billTo->setCountry($billing_address->country->name);
        $billTo->setPhoneNumber($user->phone);
        $billTo->setfaxNumber('');

        // Create a new CustomerPaymentProfile object
        $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
        $paymentProfile->setCustomerType('individual');
        $paymentProfile->setBillTo($billTo);
        $paymentProfile->setPayment($paymentCreditCard);
        $paymentProfiles[] = $paymentProfile;

        if (!is_null($user->customer_profile_id))
        {
            // Assemble the complete transaction request
            $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
            $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);

            // Add an existing profile id to the request
            $paymentprofilerequest->setCustomerProfileId($user->customer_profile_id);
            $paymentprofilerequest->setPaymentProfile($paymentProfile);
            $paymentprofilerequest->setValidationMode("liveMode");

            // Create the controller and get the response
            $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
            {
                return ['status' => 1, 'customer_profile_id' => $user->customer_profile_id, 'payment_profile_id' => $response->getCustomerPaymentProfileId()];
            }
            else
            {
                $errorMessages = $response->getMessages()->getMessage();
                return ['status' => 0, 'message' => $errorMessages[0]->getText()];
            }
        }
        else
        {
            // Create a new CustomerProfileType and add the payment profile object
            $customerProfile = new AnetAPI\CustomerProfileType();
            $customerProfile->setDescription("Customer 2 Test PHP");
            $customerProfile->setMerchantCustomerId("M_" . $user->id . "_". time());
            $customerProfile->setEmail($user->email);
            $customerProfile->setpaymentProfiles($paymentProfiles);


            // Assemble the complete transaction request
            $request = new AnetAPI\CreateCustomerProfileRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setProfile($customerProfile);

            // Create the controller and get the response
            $controller = new AnetController\CreateCustomerProfileController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

            if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
            {
                return ['status' => 1, 'customer_profile_id' => $response->getCustomerProfileId(), 'payment_profile_id' => $response->getCustomerPaymentProfileIdList()[0]];
            }
            else
            {
                $errorMessages = $response->getMessages()->getMessage();
                return ['status' => 0, 'message' => $errorMessages[0]->getText()];
            }
        }

    }

    public static function createCustomerPaymentProfile($existingcustomerprofileid, $credit_card, $billing_address, $user)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Set credit card information for payment profile
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($credit_card->card_number);
        $creditCard->setExpirationDate($credit_card->expiry_year."-".$credit_card->expiry_month);
        $creditCard->setCardCode($credit_card->cvv);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($user->firstname);
        $billto->setLastName($user->lastname);
        $billto->setCompany('');
        $billto->setAddress($billing_address->address_1.', '.$billing_address->address_2);
        $billto->setCity($billing_address->city);
        $billto->setState($billing_address->state->name);
        $billto->setZip($billing_address->postcode);
        $billto->setCountry($billing_address->country->name);
        $billto->setPhoneNumber($user->phone);
        $billto->setfaxNumber('');

        // Create a new Customer Payment Profile object
        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setBillTo($billto);
        $paymentprofile->setPayment($paymentCreditCard);
        $paymentprofile->setDefaultPaymentProfile(true);

        $paymentprofiles[] = $paymentprofile;

        // Assemble the complete transaction request
        $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);

        // Add an existing profile id to the request
        $paymentprofilerequest->setCustomerProfileId($existingcustomerprofileid);
        $paymentprofilerequest->setPaymentProfile($paymentprofile);
        $paymentprofilerequest->setRefId($refId);
        $paymentprofilerequest->setValidationMode("liveMode");

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
            return ['status' => 1, 'customer_profile_id' => $response->getCustomerProfileId(), 'payment_profile_id' => $response->getCustomerPaymentProfileId()];
        }
        else
        {
            $errorMessages = $response->getMessages()->getMessage();
            return ['status' => 0, 'message' => $errorMessages[0]->getText()];

        }
    }

    public static function getCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId)
    {
        /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        //request requires customerProfileId and customerPaymentProfileId
        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId( $refId);
        $request->setCustomerProfileId($customerProfileId);
        $request->setCustomerPaymentProfileId($customerPaymentProfileId);

        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if(($response != null))
        {
            if ($response->getMessages()->getResultCode() == "Ok")
            {
                return ['status' => 1, 'credit_card' => $response->getPaymentProfile()->getPayment()->getCreditCard()];
            }
            else
            {
                return ['status' => 0, 'message' => $response->getMessages()->getMessage()];
            }
        }
    }

    public static function chargeCreditCard($credit_card_data, $billing_address, $total_amount, $invoice, $user)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));
        $expiry = (strlen($credit_card_data->expiry_year) === 4 ? $credit_card_data->expiry_year : '20'.$credit_card_data->expiry_year) . '-' . $credit_card_data->expiry_month;
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(str_replace(' ', '', $credit_card_data->card_number));
        $creditCard->setExpirationDate($expiry);
        $creditCard->setCardCode($credit_card_data->cvv);
        // Add the payment data to a paymentType object
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);
        // Create order information
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($invoice); //generate random invoice number
        // $order->setDescription($product);
        // Set the customer's Bill To address
        $customerAddress = new AnetAPI\CustomerAddressType();
        $customerAddress->setFirstName(substr($user->firstname, 0, 50));
        $customerAddress->setLastName(substr($user->lastname, 0, 50));
        //$customerAddress->setCompany("Souveniropolis");
        $customerAddress->setAddress(substr($billing_address->address_1.', '.$billing_address->address_2, 0, 60));
        $customerAddress->setCity(substr($billing_address->city, 0, 40));
        $customerAddress->setState(substr($billing_address->state->name, 0, 40));
        $customerAddress->setZip(substr($billing_address->postcode, 0, 20));
        $customerAddress->setCountry(substr($billing_address->country->name, 0, 60));
        // Set the customer's identifying information
        $customerData = new AnetAPI\CustomerDataType();
        $customerData->setType("individual");
        // $customerData->setId(mt_rand(10000, 99999)); //try to set unique id here
        $customerData->setEmail(substr($user->email, 0, 255));

        // Create a TransactionRequestType object and add the previous objects to it
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($total_amount);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($customerAddress);
        $transactionRequestType->setCustomer($customerData);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        $controller = new AnetController\CreateTransactionController($request);
        $response = env('ENVIRONMENT') == 'TEST' ? $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX) : $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        $data = [
            "status" => 0,
            "message" => "Something went wrong",
            "errorMessage" => "Something went wrong"
        ];

        if ($response != null)
        {
            $tresponse = $response->getTransactionResponse();
            if (($tresponse != null) && ($tresponse->getResponseCode() == "1"))
            {
                $data = [
                    "status" => 1,
                    "transaction_id" => $tresponse->getTransId(),
                    "message" => "Transaction has been successful"
                ];
            }
            elseif (!is_null($tresponse) && $tresponse->getErrors() != null)
            {
                $data = [
                    "status" => 0,
                    "errorCode" => $tresponse->getErrors()[0]->getErrorCode(),
                    "errorMessage" => $tresponse->getErrors()[0]->getErrorText(),
                    "message" => "Transaction failed"
                ];
            }
//            $data = [
//                "status" => 0,
//                "errorCode" => $response->getMessages()->getMessage()[0]->getCode(),
//                "errorMessage" => $response->getMessages()->getMessage()[0]->getText(),
//                "message" => "Transaction failed"
//            ];
            return $data;
        }
        else
        {
            return $data;
        }
    }

    public static function chargeCustomerProfile($profileid, $paymentprofileid, $amount)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($profileid);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentprofileid);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        $data = [
            "status" => 0,
            "message" => "Something went wrong",
            "errorMessage" => "Something went wrong"
        ];
//        return $response;

        if ($response != null)
        {
            if($response->getMessages()->getResultCode() == "Ok")
            {
                $tresponse = $response->getTransactionResponse();

                if ($tresponse != null && $tresponse->getMessages() != null)
                {
                    $data = [
                        'status' => 1,
                        'transaction_id' => $tresponse->getTransId(),
                        'message' => 'Transaction has been successful'
                    ];
                }
                else
                {
                    if($tresponse->getErrors() != null)
                    {
                        $data = [
                            'status' => 0,
                            'errorMessage' => $tresponse->getErrors()[0]->getErrorText(),
                            'message' => 'Transaction has been failed'
                        ];
                    }
                }
            }
            else
            {
                $tresponse = $response->getTransactionResponse();
                if($tresponse != null && $tresponse->getErrors() != null)
                {
                    $data = [
                        'status' => 0,
                        'errorMessage' => $tresponse->getErrors()[0]->getErrorText(),
                        'message' => 'Transaction has been failed'
                    ];
                }
                else
                {
                    $data = [
                        'status' => 0,
                        'errorMessage' => $response->getMessages()->getMessage()[0]->getText(),
                        'message' => 'Transaction has been failed'
                    ];
                }
            }
        }
        else
        {
            $data = [
                'status' => 0,
                'errorMessage' => $response->getMessages()->getMessage()[0]->getText(),
                'message' => 'No Response'
            ];
        }

        return $data;
    }

    public static function getCustomerProfileIds()
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Get all existing customer profile ID's
        $request = new AnetAPI\GetCustomerProfileIdsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $controller = new AnetController\GetCustomerProfileIdsController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        $data = [
            "status" => 0,
            "profile_ids" => [],
            "message" => "Something went wrong",
            "errorMessage" => "Something went wrong"
        ];

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
        {
            $data = [
                "status" => 0,
                "profile_ids" => $response->getIds(),
                "message" => "Get Customer Profile Ids has been successful",
            ];
        }
        else
        {
            $errorMessages = $response->getMessages()->getMessage();

            $data = [
                "status" => 0,
                "profile_ids" => [],
                "message" => "Get Customer Profile Ids has not been successful",
                "errorMessage" => $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()
            ];
        }
        return $data;
    }

    public static function getCustomerPaymentProfileList()
    {
        $payment_profiles = [];
        for ($year = 2035; $year >= 2020; $year--)
        {
            for ($month = 12; $month >= 1; $month--)
            {
                /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
                $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
                $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
                $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

                // Set the transaction's refId
                $refId = 'ref' . time();

                //Setting the paging
                $paging = new AnetAPI\PagingType();
                $paging->setLimit("1000");
                $paging->setOffset("1");

                //Setting the sorting
                $sorting = new AnetAPI\CustomerPaymentProfileSortingType();
                $sorting->setOrderBy("id");
                $sorting->setOrderDescending(false);

                //Creating the request with the required parameters
                $request = new AnetAPI\GetCustomerPaymentProfileListRequest();
                $request->setMerchantAuthentication($merchantAuthentication);
                $request->setRefId($refId);
//        $request->setPaging($paging);
//        $request->setSorting($sorting);
                $request->setSearchType("cardsExpiringInMonth");
                $request->setMonth($year."-".($month < 10 ? "0".$month : $month));

                // Controller
                $controller = new AnetController\GetCustomerPaymentProfileListController($request);
                // Getting the response
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

                $data = [
                    "status" => 0,
                    "payment_profiles" => [],
                    "message" => "Something went wrong",
                    "errorMessage" => "Something went wrong"
                ];

                if (($response != null))
                {
                    if ($response->getMessages()->getResultCode() == "Ok" && gettype($response->getPaymentProfiles()) == 'array')
                    {
                        // Success
//                echo "GetCustomerPaymentProfileList SUCCESS: " . "\n";
//                $errorMessages = $response->getMessages()->getMessage();
//                echo "Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText() . "\n";
//                echo "Total number of Results in the result set" . $response->getTotalNumInResultSet() . "\n";
                        // Displaying the customer payment profile list
//                        $payment_profiles = [];
                        foreach ($response->getPaymentProfiles() as $paymentProfile)
                        {
                            $payment_profiles[] = [
                                'customer_profile_id' => $paymentProfile->getCustomerProfileId(),
                                'payment_profile_id' => $paymentProfile->getCustomerPaymentProfileId(),
                                'credit_card_number' => $paymentProfile->getPayment()->getCreditCard()->getCardNumber()
                            ];
//                    echo "\nCustomer Profile ID: " . $paymentProfile->getCustomerProfileId() . "\n";
//                    echo "Payment profile ID: " . $paymentProfile->getCustomerPaymentProfileId() . "\n";
//                    echo "Credit Card Number: " . $paymentProfile->getPayment()->getCreditCard()->getCardNumber() . "\n";
//                    if ($paymentProfile->getBillTo() != null) {
//                        echo "First Name in Billing Address: " . $paymentProfile->getBillTo()->getFirstName() . "\n";
//                    }
                        }

                        $data = [
                            "status" => 1,
                            "payment_profiles" => $payment_profiles,
                            "message" => "Get Customer Payment Profile List has been successful",
                        ];
                    }
                    else
                    {
                        $errorMessages = $response->getMessages()->getMessage();

                        $data = [
                            "status" => 0,
                            "payment_profiles" => [],
                            "message" => "Get Customer Payment Profile List has not been successful",
                            "errorMessage" => $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()
                        ];
                    }
                }
                else
                {
                    $data = [
                        "status" => 0,
                        "payment_profiles" => [],
                        "message" => "Get Customer Payment Profile List has not been successful",
                        "errorMessage" => "Failed to get the response"
                    ];
                    // Failed to get the response
//            echo "NULL Response Error";
                }
            }
        }

        return $payment_profiles;
    }

    public static function deleteCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZE_PAYMENT_API_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZE_PAYMENT_TRANSACTION_KEY'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Use an existing payment profile ID for this Merchant name and Transaction key

        $request = new AnetAPI\DeleteCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setCustomerProfileId($customerProfileId);
        $request->setCustomerPaymentProfileId($customerPaymentProfileId);
        $controller = new AnetController\DeleteCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        $data = [
            "status" => 0,
            "message" => "Something went wrong",
            "errorMessage" => "Something went wrong"
        ];

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
            $data = [
                'status' => 1,
                'message' => 'Delete Customer Payment Profile has been successful'
            ];
        }
        else
        {
            $errorMessages = $response->getMessages()->getMessage();

            $data = [
                'status' => 0,
                'message' => 'Delete Customer Payment Profile has been failed',
                'errorMessage' => $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText()
            ];
        }
        return $data;
    }

}
