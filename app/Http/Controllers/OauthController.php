<?php

namespace App\Http\Controllers;

use App\Http\Requests\OauthToken;
use App\Models\ExternalClientAccessToken;
use App\Models\Organization;
use App\Models\Role;
use App\Models\SSO\Oauth;
use App\Http\Requests\StoreOauthRequest;
use App\Http\Requests\UpdateOauthRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Config\Repository as Config;


class OauthController extends ApiResponseController
{
    /**
     * Display a listing of the resource.
     */

    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */

    public function token(OauthToken $request)
    {

        $client = ExternalClientAccessToken::where('clientId', $request->clientId)
            ->where('clientSecret', $request->clientSecret)
            ->first();

        if ($client) {
            $token = Str::random(60);
            $client->api_token = $token;
            $client->save();

            return response()->json([
                'success' => true,
                'responseCode' => 200,
                'message' => 'Successfully Token Generate',
                'access_token' => $token,
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);

    }

    public function getStatus(Request $request)
    {
        $user = User::where('bida_oss_id', $request->bida_oss_id)->first();
        if ($user && $user->application_fee == "Paid" && $user->role->name == 'member') {
            $roleName = $user->role->name;
            $app_client = $this->config->get('app.client_domain_url');
            $token = JWTAuth::fromUser($user);
            $status = $roleName === "applicant" ? "Processing" : 'Approved';
            $statusCode = $roleName === "applicant" ? 3 : 4;
            return $this->generateResponse(200, $status, $statusCode, "The profile data has been accessed successfully", "$app_client/user-details/$token");
        } elseif ($user) {
            $roleName = $user->role->name;
            $app_client = $this->config->get('app.client_domain_url');
            $token = JWTAuth::fromUser($user);
            $status = $roleName === "applicant" ? "Processing" : 'Approved';
            $statusCode = $roleName === "applicant" ? 3 : 4;
            return $this->generateResponse(200, $status, $statusCode, "The application form has been accessed successfully", "$app_client/show-application-form/$token");
        } else {
            return [
                "responseCode" => 400,
                "message" => 'Bad Request',
            ];
        }
    }

    public function store(StoreOauthRequest $request)
    {
        try {
            DB::beginTransaction();
            $app_client = $this->config->get('app.client_domain_url');
//            $user = User::where('bida_oss_id', $request->bida_oss_id)->first();
            $user = User::where('email', $request->email)->first();
            if ($user && $user->application_fee == "Paid" && $user->role->name == "member") {
                $token = JWTAuth::fromUser($user);
                $roleName = $user->role->name;
                $status = $roleName === "applicant" ? "Processing" : 'Approved';
                $statusCode = $roleName === "applicant" ? 3 : 4;
                DB::commit();
                return $this->generateResponse(200, $status, $statusCode, "The profile data has been accessed successfully", "$app_client/show-profile-data/$token");
            } elseif ($user) {
                $token = JWTAuth::fromUser($user);
                $roleName = $user->role->name;
                $status = $roleName === "applicant" ? "Processing" : 'Approved';
                $statusCode = $roleName === "applicant" ? 3 : 4;
                DB::commit();
                return $this->generateResponse(200, $status, $statusCode, "The application form has been accessed successfully", "$app_client/show-application-form/$token");
            } else {
                $phoneNumber = $request->authorized_person_mobile_no;
                if (substr($phoneNumber, 0, 4) === '+880') {
                    $phoneNumber = '0' . substr($phoneNumber, 4);
                }
                $user = User::updateOrCreate(
                    ['bida_oss_id' => $request->bida_oss_id],
                    [
                        'name' => $request->authorized_person_name,
                        'email' => $request->email,
                        'role_id' => Role::where('name', 'applicant')->value('id'),
                        'phone' => $phoneNumber,
                        'remember_token' => null,
                        'is_verified' => 1,
                        'email_verified_at' => now(),
                    ]
                );
                Organization::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'factory_name' => $request->organization_name,
                        'applicants_name' => $user->name,
                    ]
                );
                $token = JWTAuth::fromUser($user);
                DB::commit();
                $roleName = $user->role->name;
                $status = $roleName === "applicant" ? "Processing" : 'Approved';
                $statusCode = $roleName === "applicant" ? 3 : 4;
                return $this->generateResponse(200, $status, $statusCode, "The application form has been accessed successfully", "$app_client/show-application-form/$token");
            }

        } catch (ModelNotFoundException $exception) {
            DB::rollBack();
            return [
                "responseCode" => 400,
                "message" => 'Bad Request',
            ];
        } catch (Exception $exception) {
            DB::rollBack();
            return [
                "responseCode" => 400,
                "message" => 'Bad Request',
            ];
        }
    }

    private function generateResponse($responseCode, $status, $statusCode, $message, $applicationUrl)
    {
        return [
            "responseCode" => $responseCode,
            "status" => $status,
            "status_code" => $statusCode,
            "message" => $message,
            "application_url" => $applicationUrl,
        ];
    }


    /**
     * Display the specified resource.
     */
    public function show(Oauth $oauth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Oauth $oauth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOauthRequest $request, Oauth $oauth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Oauth $oauth)
    {
        //
    }
}
