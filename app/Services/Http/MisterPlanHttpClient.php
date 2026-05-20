<?php

namespace App\Services\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MisterPlanHttpClient
{
    private string $baseUrl;
    private string $apiKey;
    private int $channelId;
    private int $timeout;
    private int $retries;
    private int $retryDelayMs;

    public function __construct(array $config)
    {
        $this->baseUrl = rtrim($config['base_url'] ?? '', '/');
        $this->apiKey = (string)($config['api_key'] ?? '');
        $this->channelId = (int)($config['channel_id'] ?? 0);
        $this->timeout = (int)($config['timeout'] ?? 15);
        $this->retries = (int)($config['retries'] ?? 2);
        $this->retryDelayMs = (int)($config['retry_delay_ms'] ?? 300);
    }

    public function post(string $path, array $body): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');

        // Ensure channel_id is present
        if (!isset($body['channel_id'])) {
            $body['channel_id'] = $this->channelId;
        }

        $attempts = $this->retries + 1;
        $lastException = null;
        for ($i = 0; $i < $attempts; $i++) {
            try {
                $start = microtime(true);
                $response = Http::withHeaders([
                    'X-API-KEY' => $this->apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])->timeout($this->timeout)
                  ->post($url, $body);

                $latencyMs = (int) ((microtime(true) - $start) * 1000);
                $status = $response->status();
                $json = $response->json();

                Log::info('MisterPlan POST', [
                    'url' => $url,
                    'status' => $status,
                    'latency_ms' => $latencyMs,
                ]);

                if ($status >= 200 && $status < 300) {
                    return is_array($json) ? $json : [];
                }

                // Retry on 429/5xx
                if ($status == 429 || ($status >= 500 && $status <= 599)) {
                    usleep($this->retryDelayMs * 1000);
                    continue;
                }

                // For 4xx non-retriable, throw
                $msg = is_array($json) ? json_encode($json) : (string) $response->body();
                throw new Exception("MisterPlan error {$status}: {$msg}");
            } catch (Exception $e) {
                $lastException = $e;
                // On last attempt, rethrow
                if ($i === $attempts - 1) {
                    Log::error('MisterPlan POST failed', [
                        'url' => $url,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
                // backoff before retry
                usleep($this->retryDelayMs * 1000);
            }
        }

        // Should not reach here
        throw $lastException ?? new Exception('Unknown MisterPlan client error');
    }
}
