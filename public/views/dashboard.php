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
<div class="header">
    <h1>Welcome User <?= $_SESSION['user_id'] ?></h1>
    <a href="/logout">Logout</a>
</div>
    <div class="dashboard-container">
        <div class="summary-grid">
            <div class="card income">
                <h3>Monthly Income</h3>
                <span id="income-summary">0.00</span>
            </div>
            <div class="card expense">
                <h3>Monthly Expenses</h3>
                <span id="expense-summary">0.00</span>
            </div>
        </div>


        <div class="chart-container">
            <canvas id="spending-chart"></canvas>
        </div>
        <div class="transaction-form">
    <h2>Add New Transaction</h2>
    <form id="addTransactionForm">
        <div class="form-group">
            <label>Amount:</label>
            <input type="number" step="0.01" name="amount" required>
        </div>
        
        <div class="form-group">
            <label>Description:</label>
            <input type="text" name="description" required>
        </div>
        
        <div class="form-group">
            <label>Type:</label>
            <select name="type" required>
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Category:</label>
            <input type="text" name="category" required>
        </div>
        
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" required value="<?= date('Y-m-d') ?>">
        </div>
        
        <button type="submit">Add Transaction</button>
    </form>
</div>
<script>
async function loadDashboard() {
    try {
        const response = await fetch('/api/transactions');
        if (response.status === 401) {
            window.location = '/login';
            return;
        }

        const transactions = await response.json();
        
        // Update summary cards
        const income = transactions
            .filter(t => t.type === 'income')
            .reduce((sum, t) => sum + parseFloat(t.amount), 0);
        
        const expenses = transactions
            .filter(t => t.type === 'expense')
            .reduce((sum, t) => sum + Math.abs(parseFloat(t.amount)), 0);

        document.getElementById('income-summary').textContent = `${income.toFixed(2)}`;
        document.getElementById('expense-summary').textContent = `${expenses.toFixed(2)}`;

        // Update transactions table
        const tbody = document.querySelector('#transactions-table tbody');
        tbody.innerHTML = transactions.map(t => `
            <tr>
                <td>${new Date(t.transaction_date).toLocaleDateString()}</td>
                <td>${t.description || ''}</td>
                <td>${t.category}</td>
                <td class="${t.type}">${parseFloat(t.amount).toFixed(2)}</td>
            </tr>
        `).join('');

        // Update chart
        updateChart(transactions);

    } catch (error) {
        console.error('Error loading dashboard:', error);
    }
}

// Initialize chart
let chartInstance;
function updateChart(transactions) {
    const ctx = document.getElementById('spending-chart').getContext('2d');
    
    // Destroy existing chart instance
    if (chartInstance) {
        chartInstance.destroy();
    }

    // Group by category
    const categories = [...new Set(transactions.map(t => t.category))];
    const data = categories.map(category => {
        return transactions
            .filter(t => t.category === category)
            .reduce((sum, t) => sum + Math.abs(parseFloat(t.amount)), 0);
    });

    chartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                    '#9966FF', '#FF9F40', '#FFCD56', '#C9CBCF'
                ]
            }]
        }
    });
}
document.addEventListener('DOMContentLoaded', () => {
    loadDashboard();
});

</script>

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