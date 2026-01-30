<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class CashService
{
    /**
     * Prepare Cash to BFS exchange verification by generating OTP
     *
     * @param int $userId User ID
     * @param string $fullNumber User's active phone number for SMS notification
     * @return array Result array with success status, OTP code, and verification params
     */
    public function prepareCashToBfsExchange(int $userId, string $fullNumber): array
    {
        try {
            // Generate 4-digit OTP code for exchange
            $otpCode = (string)random_int(1000, 9999);

            // Update user with OTP code
            User::where('id', $userId)->update(['activationCodeValue' => $otpCode]);

            return [
                'success' => true,
                'otpCode' => $otpCode,
                'verificationParams' => [
                    'type' => 'warning',
                    'title' => 'Opt',
                    'text' => '',
                    'FullNumber' => $fullNumber
                ],
                'shouldNotifyBySms' => true
            ];

        } catch (\Exception $exception) {
            Log::error('Error preparing cash to BFS exchange: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error preparing exchange verification'
            ];
        }
    }

    /**
     * Verify OTP and execute Cash to BFS exchange operation
     *
     * @param int $userId User ID
     * @param string $code OTP code to verify
     * @param string $storedOtp Stored OTP code from user
     * @return array Result array with success status and message
     */
    public function verifyCashToBfsExchange(int $userId, string $code, string $storedOtp): array
    {
        try {
            // Verify OTP code
            if ($code != $storedOtp) {
                return [
                    'success' => false,
                    'message' => 'Invalid OPT code'
                ];
            }

            return [
                'success' => true,
                'message' => 'OTP verified successfully'
            ];

        } catch (\Exception $exception) {
            Log::error('Error verifying cash to BFS exchange OTP: ' . $exception->getMessage());
            return [
                'success' => false,
                'message' => 'Error verifying OTP'
            ];
        }
    }
}
