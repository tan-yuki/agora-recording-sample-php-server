<?php
declare(strict_types=1);


namespace UnitTest\AgoraServer\Application\Controller\SecureToken\GetSecureToken;


use AgoraServer\Application\Controller\SecureToken\GetSecureToken\GetSecureTokenRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Factory\ServerRequestFactory;

class GetSecureTokenRequestTest extends TestCase
{
    /**
     * 正常なリクエスト値
     */
    private const VALID_PARAMS = [
        GetSecureTokenRequest::PARAM_CHANNEL_NAME => 'sample_channel_name',
        GetSecureTokenRequest::PARAM_USER_ID => '1234',
    ];

    private function createServerRequest(array $params): ServerRequestInterface
    {
        $factory = new ServerRequestFactory();

        return $factory->createServerRequest(
            'GET',
            'https://test.example.com?' . http_build_query($params)
        );
    }

    public function invalidRequestDataProvider(): array
    {
        return [
            'channelNameがない' => [
                array_values(array_diff_key(self::VALID_PARAMS, [
                    GetSecureTokenRequest::PARAM_CHANNEL_NAME,
                ]))
            ],
            'userIdがない' => [
                array_values(array_diff_key(self::VALID_PARAMS, [
                    GetSecureTokenRequest::PARAM_USER_ID,
                ]))
            ],
            'userIdが文字列' => [
                array_merge(self::VALID_PARAMS, [
                    GetSecureTokenRequest::PARAM_USER_ID => 'hogehoge'
                ]),
            ],
        ];
    }

    /**
     * @test
     * @dataProvider invalidRequestDataProvider
     * @param array $params
     */
    public function throw_bad_request_exception_when_request_is_invalid(array $params): void
    {
        $request = new GetSecureTokenRequest();
        try {
            $request->validate($this->createServerRequest($params));
            $this->assertTrue(false, sprintf("params:%s is not occurred validate error", json_encode($params)));
        } catch (HttpBadRequestException $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     * @throws HttpBadRequestException
     */
    public function return_valid_params_when_request_is_invalid(): void
    {
        $request = new GetSecureTokenRequest();
        $new_params = $request->validate($this->createServerRequest(self::VALID_PARAMS));

        $this->assertCount(count(self::VALID_PARAMS), $new_params);
    }
}