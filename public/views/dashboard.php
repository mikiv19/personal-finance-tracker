<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Dashboard</title>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard-container">
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

        <div class="chart-container">
            <canvas id="spending-chart"></canvas>
        </div>

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
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/charts.js"></script>
</body>
</html>