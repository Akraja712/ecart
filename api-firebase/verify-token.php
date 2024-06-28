<?php
// header('Access-Control-Allow-Origin: *');
include_once('../includes/crud.php');
$db = new Database();
include_once('../library/jwt.php');
include_once('../includes/custom-functions.php');
$fn = new custom_functions();

function generate_token()
{
    $jwt = new JWT();
    $payload = [
        'iat' => time(), /* issued at time */
        'iss' => 'eKart',
        'exp' => time() + (30 * 60), /* expires after 1 minute */
        'sub' => 'eKart Authentication',
        'web' => '33201609',
        'app' => '31977632'
    ];

    $token = $jwt::encode($payload, JWT_SECRET_KEY);
    return $token;
}

function verify_token()
{
    $jwt = new JWT();
    $fn = new custom_functions;
    try {
        //    echo "Token : ".$token = $jwt->getBearerToken();
        $token = $jwt->getBearerToken();
    } catch (Exception $e) {
        $response['error'] = true;
        $response['message'] = $e->getMessage();
        print_r(json_encode($response));
        return false;
    }
    if (!empty($token)) {
        try {
            // JWT::$leeway = 60;
            $payload = $jwt->decode($token, JWT_SECRET_KEY, ['HS256']);
            if (!isset($payload->iss) || $payload->iss != 'eKart') {
                $response['error'] = true;
                $response['message'] = 'Invalid Hash';
                print_r(json_encode($response));
                return false;
            } else {
                if (isset($payload->web) && $payload->web == '33201609') {
                    /* fetch doctor_brown web and match item id here*/
                    $purchase_code = $fn->get_settings('doctor_brown_web');
                    $cal_time_check = $time_check = '';
                    if (!empty($purchase_code)) {
                        $isAuthWeb = json_decode($purchase_code);
                        $time_check = $isAuthWeb->time_check;
                        $token_web = trim($isAuthWeb->dr_firestone);
                    }

                    if ($token_web == '33201609') {
                        return true;
                    } else {

                        $response['error'] = true;
                        $response['message'] = 'Web purchase is not verified! Kindly check your purchase code registration!';
                        print_r(json_encode($response));
                        return false;
                    }
                }

                if (isset($payload->app) && $payload->app == '31977632') {
                    /* fetch doctor_brown app*/
                    $purchase_code = $fn->get_settings('doctor_brown');
                    $cal_time_check = $time_check = '';
                    if (!empty($purchase_code)) {
                        $isAuthApp = json_decode($purchase_code);
                        $time_check = $isAuthApp->time_check;
                        $token_app = trim($isAuthApp->dr_firestone);
                    }

                    return true;
                }
                // return true;
            }
        } catch (Exception $e) {
            $response['error'] = true;
            $response['message'] = $e->getMessage();
            print_r(json_encode($response));
            return false;
        }
    } else {
        $response['error'] = true;
        $response['message'] = "Unauthorized access not allowed";
        print_r(json_encode($response));
        return false;
    }
}
