<?php

namespace Oneup\FlysystemBundle\Adapter;

use League\Flysystem\Adapter\Local;
use Symfony\Component\HttpFoundation\RequestStack;

class LocalWithHost extends Local
{
    private $requestStack;
    private $scheme = "http";
    private $httpHost = "localhost";
    private $port = 80;
    private $webpath;

    public function __construct(
        $root,
        RequestStack $requestStack,
        $webpath,
        $defaults
    ) {
        parent::__construct($root);

        $request = $requestStack->getCurrentRequest();
        if (null !== $request) {
            $this->scheme = $request->getScheme();
            $this->httpHost = $request->getHttpHost();
            $this->port = $request->getPort();
        } else {
            if (isset($defaults['httpHost'])) {
                $this->httpHost = $defaults['httpHost'];
            }
            if (isset($defaults['port'])) {
                $this->port = $defaults['port'];
            }
            if (isset($defaults['scheme'])) {
                $this->scheme = $defaults['scheme'];
            }
        }

        $this->webpath = $webpath;
    }

    public function getBasePath()
    {
        $port = "";
        if (
            ('http' === $this->scheme && 80 !== $this->port) ||
            ('https' === $this->scheme && 443 !== $this->port)
        ) {
            $port = ":" . $this->port;
        }

        $basePath = sprintf(
          '%s://%s%s',
          $this->scheme,
          $this->httpHost,
          $port
        );
        return $basePath;
    }

    public function getWebpath()
    {
        return $this->webpath;
    }
}
