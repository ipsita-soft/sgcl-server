<?php
namespace App\Extensions;
namespace App\Extensions;

use App\Models\ExternalClientAccessToken;
use Illuminate\Auth\TokenGuard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use App\Models\ExternalClient;

class CustomTokenGuard extends TokenGuard
{
    public function __construct(UserProvider $provider, Request $request)
    {
        parent::__construct($provider, $request);
    }

    function getTokenForRequest()
    {
        $token = $this->request->bearerToken();

        if (empty($token)) {
            $token = $this->request->input('api_token');
        }

        return $token;
    }

    protected function retrieveUserByToken($token)
    {
        if (!$token) {
            return null;
        }

        return ExternalClientAccessToken::where('clientSecret', $token)->first();
    }
}
