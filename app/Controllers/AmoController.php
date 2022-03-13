<?php

namespace App\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;

class AmoController 
{
    public function amoSendDeal(Request $request)
    {
        $data = $request->validate([
            "amo_options" => "required",
            "name" => "required",
            "email" => "required|email",
            "phone" => "required|size:11",
            "price" => "required"
        ]);
        $amo_options = json_decode($data["amo_options"], true);
        $init = Amo::init($amo_options);
        $options = $init["options"];
        $apiClient = $init["apiClient"];
        $leadsService = $apiClient->leads();
        $contactsService = $apiClient->contacts();
        $contact = new ContactModel();
        $contact->setName($data["name"]);
        
        $contact->setCustomFieldsValues(
            (new CustomFieldsValuesCollection())
                ->add(
                    (new MultitextCustomFieldValuesModel())
                        ->setFieldCode('PHONE')
                        ->setValues(
                            (new MultitextCustomFieldValueCollection())
                                ->add(
                                    (new MultitextCustomFieldValueModel())
                                        ->setValue($data['phone'])
                                )
                        )
                )
                ->add(
                    (new MultitextCustomFieldValuesModel())
                        ->setFieldCode('EMAIL')
                        ->setValues(
                            (new MultitextCustomFieldValueCollection())
                                ->add(
                                    (new MultitextCustomFieldValueModel())
                                        ->setValue($data['email'])
                                )
                        )
                )

        );

        $lead = new LeadModel();
        $lead->setName('Тестовый лид');
        $lead->setPrice($data["price"]);
        $leed_response = $leadsService->addOne($lead);
        $call_amo_leed_id = $leed_response->getId();
        $response_contact = $contactsService->addOne($contact);
        $call_contact_id = $response_contact->getId();
        if ($call_contact_id && $call_amo_leed_id) {
            $new_leed = new LeadModel();
            $new_leed->setId($call_amo_leed_id);

            $new_contact = new ContactModel();
            $new_contact->setId($call_contact_id);

            $LinksCollection = new LinksCollection();
            $LinksCollection->add($new_contact);

            $leadsService->link($new_leed, $LinksCollection);
        }

        return $options;
    }

    public function addAmoIntegration($amo_code)
    {

        $amoConf = config('amo');
        $clientId = $amoConf['clientId'];
        $clientSecret = $amoConf['clientSecret'];
        $redirectUri = $amoConf['redirectUri'];
        $base_domain = $amoConf['base_domain'];
        $apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        $OAuthClient = $apiClient->getOAuthClient();
        $apiClient->setAccountBaseDomain($base_domain);
        $accessTokenArr = $OAuthClient->getAccessTokenByCode($amo_code);

        $options = [
            'access_token' => $accessTokenArr->getToken(),
            'refresh_token' => $accessTokenArr->getRefreshToken(),
            'expires' => $accessTokenArr->getExpires(),
            'base_domain' => $base_domain,
        ];

        return $options;
    }
}
