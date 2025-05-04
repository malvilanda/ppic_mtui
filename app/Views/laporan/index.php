<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Laporan</h1>
        <p class="mt-2 text-gray-600">Ringkasan dan analisis data transaksi perusahaan</p>
    </div>

    <!-- Quick Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Transaksi -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-exchange-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <h3 class="text-xl font-semibold text-gray-800">2,451</h3>
                </div>
            </div>
        </div>

        <!-- Total Bahan Baku -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Bahan Baku</p>
                    <h3 class="text-xl font-semibold text-gray-800">1,287</h3>
                </div>
            </div>
        </div>

        <!-- Total Tabung -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-flask text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Tabung</p>
                    <h3 class="text-xl font-semibold text-gray-800">3,890</h3>
                </div>
            </div>
        </div>

        <!-- Total Nilai -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Nilai</p>
                    <h3 class="text-xl font-semibold text-gray-800">Rp 4.5M</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Laporan Bahan Baku Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Laporan Bahan Baku</h2>
                        <p class="text-gray-600 mt-1">Analisis transaksi bahan baku masuk dan keluar</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-boxes text-2xl"></i>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                        <span class="text-gray-600">Total Masuk:</span>
                        <span class="ml-auto font-semibold">1,234 item</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                        <span class="text-gray-600">Total Keluar:</span>
                        <span class="ml-auto font-semibold">987 item</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <a href="<?= base_url('laporan/bahanbaku') ?>" 
                       class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <span>Lihat Detail Laporan</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Laporan Tabung Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Laporan Tabung</h2>
                        <p class="text-gray-600 mt-1">Analisis transaksi tabung masuk dan keluar</p>
                    </div>
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-flask text-2xl"></i>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                        <span class="text-gray-600">Total Masuk:</span>
                        <span class="ml-auto font-semibold">2,567 unit</span>
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                        <span class="text-gray-600">Total Keluar:</span>
                        <span class="ml-auto font-semibold">2,145 unit</span>
                    </div>
                    <div class="h-px bg-gray-200"></div>
                    <a href="<?= base_url('laporan/tabung') ?>" 
                       class="inline-flex items-center justify-center w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <span>Lihat Detail Laporan</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Features -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quick Export -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Export Laporan</h3>
            <div class="space-y-3">
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                    Export ke Excel
                </button>
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                    Export ke PDF
                </button>
            </div>
        </div>

        <!-- Quick Filter -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Cepat</h3>
            <div class="space-y-3">
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Bulan Ini
                </button>
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar-week text-purple-600 mr-2"></i>
                    Minggu Ini
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-print text-gray-600 mr-2"></i>
                    Cetak Laporan
                </button>
                <button class="flex items-center justify-center w-full px-4 py-2 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-share-alt text-gray-600 mr-2"></i>
                    Bagikan Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effect to cards
    const cards = document.querySelectorAll('.hover\\:shadow-lg');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('transform', 'scale-102');
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('transform', 'scale-102');
        });
    });

    // Add click effect to buttons
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            this.classList.add('scale-95');
            setTimeout(() => {
                this.classList.remove('scale-95');
            }, 100);
        });
    });
});
</script>

<style>
.scale-102 {
    transform: scale(1.02);
}
.transition-shadow {
    transition: box-shadow 0.3s ease-in-out, transform 0.3s ease-in-out;
}
</style>
<?= $this->endSection(); ?> 