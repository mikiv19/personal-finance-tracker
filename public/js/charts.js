document.addEventListener('DOMContentLoaded', async () => {
    try {
        const [summaryRes, transactionsRes] = await Promise.all([
            fetch('/api/summary'),
            fetch('/api/transactions')
        ]);

        if (!summaryRes.ok || !transactionsRes.ok) {
            const errorText = await summaryRes.text();
            console.error('API Error:', errorText);
            throw new Error('API request failed');
        }

        const [summary, transactions] = await Promise.all([
            summaryRes.json(),
            transactionsRes.json()
        ]);

        if (summary.error) throw new Error(summary.error);
        if (transactions.error) throw new Error(transactions.error);

        updateSummaryCards(summary);
        renderTransactions(transactions);
        initChart(summary.by_category);

    } catch (error) {
        console.error('Dashboard Error:', error);
        showError(`Data load failed: ${error.message}`);
    }
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <div class="alert">
                ${message}
            </div>
        `;
        document.body.prepend(errorDiv);
    }
});