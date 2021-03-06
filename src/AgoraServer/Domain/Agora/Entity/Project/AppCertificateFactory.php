<?php
declare(strict_types=1);


namespace AgoraServer\Domain\Agora\Entity\Project;

use AgoraServer\Domain\Agora\Entity\Project\AppCertificate;
use AgoraServer\Infrastructure\Env\EnvironmentVariable;

final class AppCertificateFactory
{
    private EnvironmentVariable $env;
    public function __construct(EnvironmentVariable $env)
    {
        $this->env = $env;
    }

    public function create(): AppCertificate
    {
        return new AppCertificate($this->env->getAppCertificate());
    }

}