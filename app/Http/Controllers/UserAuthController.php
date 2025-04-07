<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetRequest;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendVerificationEmail;
use Illuminate\Support\Facades\Password;

class UserAuthController extends ApiResponseController
{
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->errorResponse(message: 'Invalid Credentials', status: 401);
        }

        return (new UserResource(auth()->user()))->additional([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


//    Registration
    public function register(RegisterRequest $request)
    {
        $clientDomainUrl = $request->headers->get('referer');
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => Role::where('name', 'applicant')->value('id'),
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ]);

            $credentials = request(['email', 'password']);

            if (!$token = auth()->attempt($credentials)) {
                return $this->errorResponse(message: 'Invalid Credentials', status: 401);
            }
            $api = $request->fullUrlWithoutQuery(["api/register"]);

            $random = Str::random(40);
            $url = $clientDomainUrl . 'verify-mail/' . $random;
            $data = [
                'url' => $url,
                'email' => $user->email,
                'title' => 'Email Verification',
                'body' => 'Please click the link below to verify your email.',
            ];

            Queue::push(new SendVerificationEmail($data));
            $user->update(['remember_token' => $random]);

            DB::commit();

            return (new UserResource(auth()->user()))->additional([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'message' => 'Registration successful! Please check your mail with email verify',
            ]);


        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }


    public function updateImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,,gif|max:2048',
        ]);
        try {
            DB::beginTransaction();
            $user = $request->user();
            if ($user->profile_image) {
                $currentImagePath = str_replace('storage/', '', $user->profile_image);
                Storage::disk('public')->delete($currentImagePath);
            }
            $file = $request->file('profile_image');
            $yearDirectory = '/profiles/' . date('Y') . '/';
            $storedFileName = 'profile_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs($yearDirectory, $file, $storedFileName);
            $storedFilePath = 'storage' . $yearDirectory . $storedFileName;
            $user->profile_image = $storedFilePath;
            $user->save();
            DB::commit();
            return $this->successResponse(
                message: 'Profile image updated successfully', data: [
                'profile_image' => asset($storedFilePath),
            ]
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $request->user();
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'message' => 'The provided old password is incorrect.',
                ], 400);
            }
            $user->password = Hash::make($request->new_password);
            $user->save();
            DB::commit();
            return $this->successResponse(
                message: 'Password changed successfully'
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }


    }


    public function sendVerifyMail(Request $request, $email)
    {
        if (!auth()->check()) {
            return $this->errorResponse(
                message: 'User is not authenticated.'
            );
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->errorResponse(
                message: 'User not found.'
            );
        }

        $clientDomainUrl = $request->headers->get('referer');

        $random = Str::random(40);
        $url = $clientDomainUrl . 'verify-mail/' . $random;
        $data = [
            'url' => $url,
            'email' => $email,
            'title' => 'Email Verification',
            'body' => 'Please click the link below to verify your email.',
        ];

        Queue::push(new SendVerificationEmail($data));

        $user->update(['remember_token' => $random]);

        return $this->successResponse(
            message: 'Email sent successfully.'
        );
    }


    public function verificationMail($token)
    {
        try {
            DB::beginTransaction();
            $user = User::where(["remember_token" => $token])->firstOrFail();
            $user->update([
                'remember_token' => null,
                'is_verified' => 1,
                'email_verified_at' => now()
            ]);

            Organization::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'applicants_name' => $user->name,
                    'user_id' => $user->id,
                ]
            );

            DB::commit();

            return (new UserResource($user))->additional([
                'message' => 'Email verified successfully.',
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->dataNotFoundResponse();
        } catch (Exception $exception) {
            return $this->errorResponse(
                message: $exception->getMessage(),
            );
        }

    }


    public function forgot(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email'),
            );

            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Reset link sent to your email , to your mail '], 200)
                : response()->json(['message' => 'Unable to send reset link'], 500);
        } else {
            return $this->errorResponse(
                message: 'we cannot find your email.'
            );
        }


    }


    public function changePass(ResetRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        return $status == Password::PASSWORD_RESET ? response()->json(['message' => 'Password reset successfully'], 200)
            : ['message' => 'Unable to reset password'];
    }


    public function user()
    {
        return (new UserResource(Auth::user()));
    }

    public function logout()
    {
        auth()->logout();

        return $this->successResponse(message: 'Logged Out');
    }
}
