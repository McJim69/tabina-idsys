<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
	body {
		font-family: 'Outfit', sans-serif;
	}
	.analytics-card {
		border-radius: 16px;
		transition: all 0.2s ease;
	}
	[data-theme="dark"] .analytics-card {
		background-color: var(--bg-secondary) !important;
		border-color: var(--border-color) !important;
	}
	[data-theme="dark"] .card-header {
		background-color: var(--bg-tertiary) !important;
		border-bottom-color: var(--border-color) !important;
	}
	.metric-num {
		font-size: 2.2rem;
		font-weight: 800;
	}
</style>