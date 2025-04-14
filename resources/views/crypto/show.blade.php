<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $coin['name'] }} - EzCrypto Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <a href="{{ route('crypto.index') }}" class="btn btn-secondary mb-4">← Back to list</a>


        <h1 class="mb-3">{{ $coin['name'] }} ({{ strtoupper($coin['symbol']) }})</h1>
        <p class="fs-5">Current Price: <strong>${{ number_format($coin['market_data']['current_price']['usd'], 2) }}</strong></p>

        <div class="card bg-secondary p-4 mb-4">
            <h4>7-Day Price Trend</h4>
            <div style="height: 400px;">
                <canvas id="priceChart"></canvas>
            </div>
        </div>
        
        <script>
            const priceData = @json($prices);
        
            const labels = priceData.map(p => {
                const date = new Date(p[0]);
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            });
        
            const data = priceData.map(p => p[1]);
        
            const ctx = document.getElementById('priceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Price (USD)',
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0 // Smooth line
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { color: 'white' }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        x: { ticks: { color: 'white' } },
                        y: { ticks: { color: 'white' } }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        </script>
        
</body>
</html>
