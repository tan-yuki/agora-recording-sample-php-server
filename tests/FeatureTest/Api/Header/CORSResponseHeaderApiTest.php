<?php
declare(strict_types=1);


namespace FeatureTest\Api\Header;


use AgoraServer\Application\Controller\SecureToken\GetSecureToken\GetSecureTokenRequest;
use FeatureTest\FeatureBaseTestCase;

class CORSResponseHeaderApiTest extends FeatureBaseTestCase
{
    /**
     * @test
     */
    public function return_secure_token()
    {
        $response = $this->runApp('GET', '/v1/token?' . http_build_query([
                GetSecureTokenRequest::PARAM_USER_ID => '1234',
                GetSecureTokenRequest::PARAM_CHANNEL_NAME=> 'channel',
            ]));

        $this->assertNotEmpty($response->getHeader('Access-Control-Allow-Origin'));
    }
}