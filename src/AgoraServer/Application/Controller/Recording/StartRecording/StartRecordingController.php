<?php
declare(strict_types=1);

namespace AgoraServer\Application\Controller\Recording\StartRecording;

use AgoraServer\Application\Controller\Base\ControllerInterface;
use AgoraServer\Application\Controller\SecureToken\GetSecureToken\GetSecureTokenRequest;
use AgoraServer\Application\Shared\ResponseWithJsonTrait;
use AgoraServer\Domain\Agora\Entity\ChannelName;
use AgoraServer\Domain\Agora\Entity\Project\SecureToken;
use AgoraServer\Domain\Agora\Entity\UserId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;


final class StartRecordingController implements ControllerInterface
{
    use ResponseWithJsonTrait;

    private StartRecordingRequest $request;
    private StartRecordingUseCase $useCase;

    public function __construct(StartRecordingRequest $request,
                                StartRecordingUseCase $useCase)
    {
        $this->request = $request;
        $this->useCase = $useCase;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @return ResponseInterface
     * @throws HttpBadRequestException
     */
    public function execute(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $validParams = $this->request->validate($request);
        $useCase = $this->useCase;

        $dto = $useCase(
            new ChannelName($validParams[StartRecordingRequest::PARAM_CHANNEL_NAME]),
            new UserId($validParams[StartRecordingRequest::PARAM_USER_ID]),
            new SecureToken($validParams[StartRecordingRequest::PARAM_TOKEN]),
        );

        return $this->withJson($response, $dto->toArray());
    }

}