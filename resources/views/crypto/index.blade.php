<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EzCrypto Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="mb-4">Top 10 Cryptocurrencies</h1>

        <form action="{{ route('crypto.search') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search by name or symbol..." value="{{ $searchQuery ?? '' }}">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>
        
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Coin</th>
                    <th>Price</th>
                    <th>Market Cap</th>
                    <th>24h Change</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coins as $index => $coin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ $coin['image'] }}" width="20"> 
                        <a href="{{ route('crypto.show', trim($coin['id'])) }}">
                            {{ $coin['name'] }} ({{ strtoupper($coin['symbol']) }})
                        </a>
                    </td>
                    <td>${{ number_format($coin['current_price'], 2) }}</td>
                    <td>${{ number_format($coin['market_cap']) }}</td>
                    <td class="{{ $coin['price_change_percentage_24h'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($coin['price_change_percentage_24h'], 2) }}%
                    </td>
                </tr>
            @endforeach
            
            </tbody>
        </table>
    </div>
</body>
</html>
