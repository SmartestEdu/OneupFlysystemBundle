<?php

namespace Oneup\FlysystemBundle\Adapter;

use League\Flysystem\Adapter\Local;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalWithHost extends Local
{
    private $requestStack;
    private $scheme;
    private $httpHost;
    private $port;
    private $webpath;

    public function __construct(
        $root,
        RequestStack $requestStack,
        $webpath
    ) {
        parent::__construct($root);
        $request = $requestStack->getCurrentRequest();
        $this->scheme = $request->getScheme();
        $this->httpHost = $request->getHttpHost();
        $this->port = $request->getPort();
        $this->webpath = $webpath;
    }

    public function getBasePath()
    {
        $basePath = sprintf(
          '%s://%s:%s',
          $this->scheme,
          $this->httpHost,
          $this->port
        );
        return $basePath;
    }

    public function getWebpath()
    {
        return $this->webpath;
    }
}
