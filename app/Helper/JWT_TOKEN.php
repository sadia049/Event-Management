<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWT_TOKEN {
    /**
     * Generate JWT Token
     * @param string $user_email
     * @return string
     */
    public static function create_token( $user_email,$user_id ): string{
        $key = env( 'JWT_KEY' );
        $payload = [
            'iss' => 'pos-token',
            'iat' => time(),
            'exp' => time() + ( 60 * 60 * 2 ), // expired after 2 hours
            'user_email' => $user_email,
            'user_id' =>$user_id
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }
    /**
     * Generate JWT Token
     * @param string $token
     * @return mixed
     */
    public static function verify_token( $token ) {
        try {
            if($token==null){
                return 'unauthorized';
            }
            else{
                $key = env( 'JWT_KEY' );
                $decode = JWT::decode( $token, new Key( $key, 'HS256' ) );
                //echo $decode->exp;
                return $decode;
            }
           
        } catch ( \Throwable $th ) {
            return "unauthorized" ;
        }

    }

    /**
     * Generate JWT Token for Reset password
     * @param string $user_email
     * @return string
     */
    public static function reset_token( $user_email ): string{
        $key = env( 'JWT_KEY' );
        $payload = [
            'iss' => 'pos-token',
            'iat' => time(),
            'exp' => time() +   ( 60 * 60 * 2 ), // expired after 5 minutes
            'user_email' => $user_email,
            'user_id' =>'0'
        ];
        return JWT::encode( $payload, $key, 'HS256' );
    }
}