<?php

namespace App;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class Amo
{

    static function init($options)
    {

        $amoConf = config('amo');
        $clientId = $amoConf['clientId'];
        $clientSecret = $amoConf['clientSecret'];
        $redirectUri = $amoConf['redirectUri'];
        $apiClient = null;
        try {
            $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
            $accessToken = new AccessToken($options);

            $apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($options['base_domain'])
                ->onAccessTokenRefresh(
                    function (AccessTokenInterface $accessToken, string $baseDomain) use(&$options) {

                        /// Требуется тут обновлять информацю с опцямии в базе 
                        $options = json_encode([
                            'access_token' => $accessToken->getToken(),
                            'refresh_token' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'base_domain' => $baseDomain,
                        ]);
                    }
                );
        } catch (Exception $ex) {
            // log this somewhere

            abort(403, "Ошибка при подключении Амо");
        }
        return ["apiClient" => $apiClient, "options" => $options];
    }
}
