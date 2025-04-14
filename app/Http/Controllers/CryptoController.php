<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CryptoController extends Controller
{
    public function index()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://api.coingecko.com/api/v3/coins/markets', [
            'query' => [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 10,
                'page' => 1,
                'sparkline' => false
            ]
        ]);
    
        $coins = json_decode($response->getBody(), true);
    
        return view('crypto.index', compact('coins'));
    }
    

    public function search(Request $request)
    {
        $query = $request->input('query');
        $allCoins = collect();

        for ($page = 1; $page <= 3; $page++) {
            $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 250,
                'page' => $page,
                'sparkline' => false,
            ]);

            if ($response->ok()) {
                $allCoins = $allCoins->merge($response->json());
            }
        }

        if ($query) {
            $allCoins = $allCoins->filter(function ($coin) use ($query) {
                return isset($coin['name'], $coin['symbol']) &&
                    (stripos($coin['name'], $query) !== false || stripos($coin['symbol'], $query) !== false);
            });
        }

        return view('crypto.index', [
            'coins' => $allCoins,
            'searchQuery' => $query,
        ]);
    }

    public function show($id)
    {
        $coin = Cache::remember("coin_{$id}", now()->addMinutes(10), function () use ($id) {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://api.coingecko.com/api/v3/coins/{$id}", [
                'query' => ['localization' => 'false', 'tickers' => 'false', 'community_data' => 'false', 'developer_data' => 'false']
            ]);
            return json_decode($response->getBody(), true);
        });

        $prices = Cache::remember("coin_chart_{$id}", now()->addMinutes(10), function () use ($id) {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://api.coingecko.com/api/v3/coins/{$id}/market_chart", [
                'query' => [
                    'vs_currency' => 'usd',
                    'days' => '7',
                ]
            ]);
            $chartData = json_decode($response->getBody(), true);
            return $chartData['prices'];
        });

        return view('crypto.show', compact('coin', 'prices'));
    }
    
}


