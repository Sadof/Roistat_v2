<?php

namespace App\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;

class Amo
{
    static function init($options)
    {
        $amo_config = include($_SERVER['DOCUMENT_ROOT'] .'/config/amo.php');
        $clientId = $amo_config['clientId'];
        $clientSecret = $amo_config['clientSecret'];
        $redirectUri = $amo_config['redirectUri'];
        $apiClient = null;
        try {
            $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
            $accessToken = new AccessToken($options);

            $apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($options['base_domain'])
                ->onAccessTokenRefresh(
                    function (AccessTokenInterface $accessToken, string $baseDomain) use(&$options) {

                        /// Требуется тут обновлять информацю с опцямии в базе и брать из нее опции для дальнейших инициализаций
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

        }
        return $apiClient;
    }
}
