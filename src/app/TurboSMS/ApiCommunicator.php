<?php

namespace App\TurboSMS;

use App\TurboSMS\Request\ApiRequest;
use App\TurboSMS\Exception\CommunicationErrorException;
use App\TurboSMS\Response\ApiResponse;
use App\TurboSMS\Response\ErrorApiResponse;
use Illuminate\Support\Facades\Http;

class ApiCommunicator
{
    protected Http $httpClient;

    protected string $secret;

    protected string $baseUri;

    protected bool $isAllowed;

    public function __construct(TurboSMSConfig $config)
    {
        $this->secret = $config->getSecret();
        $this->secret = $config->getSecret();
        $this->baseUri = $config->getBaseUri();
        $this->isAllowed = $config->isAllowed();
        $this->httpClient = new Http();
    }

    public function send(ApiRequest $request): ApiResponse
    {
        if (!$this->isAllowed) {
            return new ErrorApiResponse(['message' => 'Not allowed!']);
        }

        $url = $this->baseUri . $request->getQueryUrl();

        try {
            $response = Http::timeout(3)
                ->retry(2, 200)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . trim($this->secret),
                    'Content-Type' => 'application/json',
                ])->send($request->getMethod(), $url, $request->getBody() ? ['json' => $request->getBody()] : []);

            if ($response->successful()) {
                return $this->buildResponse($request, $response->json());
            } else {
                return $this->buildErrorResponse($response->json());
            }
        } catch (\Throwable $exception) {
            throw new CommunicationErrorException($exception->getMessage());
        }
    }

    private function buildResponse(ApiRequest $request, array $data): ApiResponse
    {
        $requestClassName = get_class($request);
        $responseClass = preg_replace('~Request((?=\\\)|(?=$))~', 'Response', $requestClassName);

        return new $responseClass($data);
    }

    private function buildErrorResponse(array $data): ApiResponse
    {
        return new ErrorApiResponse($data);
    }
}
