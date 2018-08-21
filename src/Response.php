<?php
/**
 * HTTP Standard Response
 * User: moyo
 * Date: 25/08/2017
 * Time: 12:47 PM
 */

namespace Carno\HTTP\Standard;

use Carno\HTTP\Standard\Utils\CodePhrases;
use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    use Helper;

    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @var string
     */
    private $reasonPhrase = 'OK';

    /**
     * Response constructor.
     * @param int $code
     * @param array $headers
     * @param string $payload
     */
    public function __construct(
        int $code = 200,
        array $headers = [],
        string $payload = null
    ) {
        parent::__construct($headers, $this->payloadToBody($payload));

        $this->withStatus($code);
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * @param int $code
     * @param string $reasonPhrase
     * @return ResponseInterface
     */
    public function withStatus($code, $reasonPhrase = '') : ResponseInterface
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase ?: CodePhrases::resolve($code);
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonPhrase() : string
    {
        return $this->reasonPhrase;
    }
}
