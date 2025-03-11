<?php 
namespace Mlab\Webhook\Helpers;

use Illuminate\Support\Facades\Facade;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Psr7\Stream;

/**
 * Http Response Class
 * 
 * @method static ResponseInterface create(int $status = 200, array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface ok(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface notFound(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface internalServerError(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface badRequest(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface unauthorized(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface forbidden(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface noContent(array $headers = [], string $body = null, string $version = '1.1', string $reason = null)
 * @method static ResponseInterface redirect(string $url, int $status = 302, array $headers = [], string $version = '1.1', string $reason = null)
 * @method static ResponseInterface json(array $data, int $status = 200, array $headers = [], string $version = '1.1', string $reason = null)
 * @method static ResponseInterface xml(string $data, int $status = 200, array $headers = [], string $version = '1.1', string $reason = null)
 * 
 * @see ResponseInterface
 * 
 * @package MLAB\Helpers
 * @final
 */
final class Response extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'http-response';
    }

    public static function json(array $data, int $statusCode = 200) 
    {
        $jsonData = json_encode($data);
        $stream = \GuzzleHttp\Psr7\Utils::streamFor($jsonData);
        
        $response = new GuzzleResponse();
        $response = $response->withHeader('Content-Type', 'application/json');
        $response = $response->withBody($stream)->withStatus($statusCode);


        return $response;
    }
    
}