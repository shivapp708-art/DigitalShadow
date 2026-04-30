<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordlessAuthController;
use App\Http\Controllers\Individual\SmartKycController;
use App\Http\Controllers\Individual\ScanController;
use App\Http\Controllers\Organization\OnboardingController;
use App\Http\Controllers\Billing\CreditController;

Route::get('/health', function () {
    return ['status' => 'ok', 'service' => 'laravel-api'];
});

Route::post('/auth/otp/send', [PasswordlessAuthController::class, 'sendOtp']);
Route::post('/auth/otp/verify', [PasswordlessAuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/individuals/kyc/initiate', [SmartKycController::class, 'initiate']);
    Route::post('/individuals/kyc/verify-aadhaar', [SmartKycController::class, 'verifyAadhaarXml']);
    Route::post('/individuals/kyc/face-match', [SmartKycController::class, 'faceMatch']);
    Route::get('/individuals/kyc/status', [SmartKycController::class, 'status']);
    Route::post('/individuals/breach/email', [ScanController::class, 'breachEmail']);
    Route::post('/individuals/username/enum', [ScanController::class, 'usernameEnum']);
    Route::get('/individuals/scans/history', [ScanController::class, 'history']);
    Route::post('/organizations/register', [OnboardingController::class, 'register']);
    Route::get('/organizations/{orgId}/verification-options', [OnboardingController::class, 'getVerificationOptions']);
    Route::post('/organizations/{orgId}/verify-domain', [OnboardingController::class, 'verifyDomain']);
    Route::get('/billing/credit-plans', [CreditController::class, 'getPlans']);
    Route::post('/billing/create-payment-intent', [CreditController::class, 'createPaymentIntent']);
});

Route::post('/billing/webhook', [CreditController::class, 'handleWebhook']);
