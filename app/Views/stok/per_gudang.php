<?= $this->include('dashboard/header') ?>

<div class="container mx-auto px-4 py-8">
    <!-- Ringkasan Gudang -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <?php foreach ($warehouses as $warehouse): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold"><?= $warehouse['name'] ?></h3>
                <span class="text-sm text-gray-500"><?= $warehouse['location'] ?></span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Transaksi</span>
                    <span class="font-semibold"><?= number_format($warehouse['total_transactions']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Barang Masuk</span>
                    <span class="text-green-600 font-semibold"><?= number_format($warehouse['total_incoming']) ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Barang Keluar</span>
                    <span class="text-red-600 font-semibold"><?= number_format($warehouse['total_outgoing']) ?></span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <h4 class="text-sm font-semibold mb-2">Stok Saat Ini:</h4>
                <?php if (isset($warehouse_stocks[$warehouse['id']])): ?>
                    <?php foreach ($warehouse_stocks[$warehouse['id']] as $stock): ?>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600"><?= $stock['name'] ?></span>
                        <span class="font-semibold"><?= number_format($stock['current_stock']) ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">Belum ada stok</p>
                <?php endif; ?>
            </div>
            <div class="mt-4">
                <a href="<?= base_url('transaksi/gudang/' . $warehouse['id']) ?>" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail →
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Grafik Perbandingan Stok Antar Gudang -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4">Perbandingan Stok Antar Gudang</h3>
        <canvas id="warehouseChart" height="300"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('warehouseChart').getContext('2d');
    const warehouseData = <?= json_encode($warehouse_stocks) ?>;
    const warehouses = <?= json_encode($warehouses) ?>;
    
    // Prepare data for chart
    const labels = warehouses.map(w => w.name);
    const datasets = [];
    
    // Get unique items across all warehouses
    const items = new Set();
    Object.values(warehouseData).forEach(stocks => {
        stocks.forEach(stock => items.add(stock.name));
    });
    
    // Create dataset for each item
    Array.from(items).forEach(itemName => {
        const data = warehouses.map(warehouse => {
            const stocks = warehouseData[warehouse.id] || [];
            const itemStock = stocks.find(s => s.name === itemName);
            return itemStock ? itemStock.current_stock : 0;
        });
        
        datasets.push({
            label: itemName,
            data: data,
            backgroundColor: getRandomColor(),
            borderColor: 'rgba(255, 255, 255, 0.8)',
            borderWidth: 1
        });
    });
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});

function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color + '80'; // Add 50% opacity
}
</script> 