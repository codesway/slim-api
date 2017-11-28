<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/28
 * Time: 15:15
 */
namespace CC\Core\Base;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AppBase extends \Slim\App
{
    public $status = null;
    public $container = [];
    public function __construct($container)
    {
        $this->status = 'init_start';
        parent::__construct($container);
        $this->container = parent::getContainer();
        $this->status = 'init_end';
    }

    public function respond(ResponseInterface $response)
    {
        $this->status = 'output_start';
        // Send response
        if (!headers_sent()) {
            // Headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }

            // Set the status _after_ the headers, because of PHP's "helpful" behavior with location headers.
            // See https://github.com/slimphp/Slim/issues/1730

            // Status
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }

        // Body
        if (!$this->isEmptyResponse($response)) {
            $body = $response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }
            $settings       = $this->container->get('settings');
            $chunkSize      = $settings['responseChunkSize'];
            $contentLength  = $response->getHeaderLine('Content-Length');
            if (!$contentLength) {
                $contentLength = $body->getSize();
            }
            if (isset($contentLength)) {
                $amountToRead = $contentLength;
                while ($amountToRead > 0 && !$body->eof()) {
                    $data = $body->read(min($chunkSize, $amountToRead));

                    echo json_encode([
                        'status' => 1,
                        'code' => 200,
                        'data' => $data,
                    ], JSON_UNESCAPED_UNICODE);

                    $amountToRead -= strlen($data);

                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            } else {
                while (!$body->eof()) {
                    echo $body->read($chunkSize);
                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            }
        }
        $this->status = 'output_end';
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $router = $this->container->get('router');
        $this->status = 'execute_start';
        return parent::__invoke($request, $response);
        $this->status = 'execute_end';
    }
}