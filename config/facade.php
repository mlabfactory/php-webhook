<?php

// Facade initialization

use GuzzleHttp\Psr7\Response;

return [
    'log' => $logger,
    'http-response' => new Response()
];