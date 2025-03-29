<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard</title>
    <link rel="stylesheet" href="/css/app.css">
    
    
</head>
<body>
    <div class="dashboard-container">
        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="card income">
                <h3>Monthly Income</h3>
                <span id="income-summary">€0.00</span>
            </div>
            <div class="card expense">
                <h3>Monthly Expenses</h3>
                <span id="expense-summary">€0.00</span>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container">
            <canvas id="spending-chart"></canvas>
        </div>

        <!-- Transaction List -->
        <div class="transaction-list">
            <h2>Recent Transactions</h2>
            <table id="transactions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filled via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/charts.js"></script>
</body>
</html>