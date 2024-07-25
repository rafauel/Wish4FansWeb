<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class FeedController extends Controller
{
    private $client;
    private $apiKey;
    private $baseUri;

    public function __construct()
    {
        $this->baseUri = "https://localhost:7108";
        $this->apiKey = "WISH";
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'verify' => false // Disable SSL verification
        ]);
    }

    public function index()
    {
        try {
            $response = $this->client->get("/User/Get?Id=1", [
                'headers' => [
                    'Accept' => 'application/json',
                    'X-API-KEY' => $this->apiKey,
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            // dd($data);
            return view('pages/feed.index', ['user_info' => $data]);
        } catch (RequestException $e) {
            Log::error('RequestException: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch data.'], 500);
        }
    }
}
