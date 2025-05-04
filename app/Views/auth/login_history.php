<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-lg p-3 mr-4">
                <i class="fas fa-history text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Login</h1>
                <p class="text-sm text-gray-500 mt-1">Catatan aktivitas login pengguna sistem</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <button class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><?= session()->getFlashdata('success'); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700"><?= session()->getFlashdata('error'); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="loginHistoryTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Login</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MAC Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perangkat</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $i = 1; ?>
                    <?php foreach ($loginHistory as $log): ?>
                        <?php 
                            $location = json_decode($log['location'], true);
                            $lastActivity = strtotime($log['last_activity'] ?? $log['login_time']);
                            $now = time();
                            $inactive = $now - $lastActivity;
                            $isOnline = $log['is_active'] && $inactive <= 1800; // 30 menit
                            
                            if ($isOnline) {
                                $activityStatus = 'Online';
                                $activityClass = 'bg-green-100 text-green-800';
                                $activityIcon = 'fa-circle text-green-500';
                                $lastSeen = 'Aktif';
                            } else {
                                $activityStatus = 'Offline';
                                $activityClass = 'bg-gray-100 text-gray-800';
                                $activityIcon = 'fa-circle text-gray-400';
                                $lastSeen = human_time_diff($lastActivity);
                            }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $i++; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= date('d/m/Y', strtotime($log['login_time'])); ?></div>
                                <div class="text-sm text-gray-500"><?= date('H:i:s', strtotime($log['login_time'])); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600"><?= strtoupper(substr($log['username'], 0, 1)); ?></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= esc($log['username']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= esc($log['ip_address']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if (!empty($log['mac_address'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-800">
                                        <i class="fas fa-network-wired text-blue-500 mr-1"></i>
                                        <?= esc($log['mac_address']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>
                                        Data tidak tersedia
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php 
                                    if ($location && isset($location['city'])): 
                                ?>
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        <div>
                                            <div class="font-medium">
                                                <?= esc($location['city']); ?>, <?= esc($location['country']); ?>
                                            </div>
                                            <?php if (isset($location['region']) && $location['region'] !== $location['city']): ?>
                                            <div class="text-xs text-gray-500">
                                                <?= esc($location['region']); ?>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (isset($location['provider'])): ?>
                                            <div class="text-xs text-gray-400">
                                                via <?= esc(ucfirst($location['provider'])); ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php elseif ($location && isset($location['ip_type'])): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <?= $location['ip_type']; ?> <?= $location['is_private'] ? '(Private)' : '(Public)'; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                                        Lokasi tidak terdeteksi
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <?php
                                        $userAgent = strtolower($log['user_agent']);
                                        $deviceIcon = 'fa-laptop'; // Default icon
                                        $deviceType = 'Desktop';

                                        // Deteksi Mobile
                                        if (strpos($userAgent, 'mobile') !== false || 
                                            strpos($userAgent, 'android') !== false || 
                                            strpos($userAgent, 'iphone') !== false) {
                                            $deviceIcon = 'fa-mobile-alt';
                                            $deviceType = 'Mobile';
                                        }
                                        // Deteksi Tablet
                                        else if (strpos($userAgent, 'ipad') !== false || 
                                                strpos($userAgent, 'tablet') !== false) {
                                            $deviceIcon = 'fa-tablet-alt';
                                            $deviceType = 'Tablet';
                                        }
                                        // Deteksi Browser
                                        $browser = 'Browser Lainnya';
                                        $browserIcon = 'fa-globe';
                                        
                                        if (strpos($userAgent, 'chrome') !== false) {
                                            $browser = 'Chrome';
                                            $browserIcon = 'fa-chrome';
                                        } else if (strpos($userAgent, 'firefox') !== false) {
                                            $browser = 'Firefox';
                                            $browserIcon = 'fa-firefox';
                                        } else if (strpos($userAgent, 'safari') !== false) {
                                            $browser = 'Safari';
                                            $browserIcon = 'fa-safari';
                                        } else if (strpos($userAgent, 'edge') !== false) {
                                            $browser = 'Edge';
                                            $browserIcon = 'fa-edge';
                                        } else if (strpos($userAgent, 'opera') !== false) {
                                            $browser = 'Opera';
                                            $browserIcon = 'fa-opera';
                                        }

                                        // Deteksi OS
                                        $os = 'OS Lainnya';
                                        $osIcon = 'fa-microchip';

                                        if (strpos($userAgent, 'windows') !== false) {
                                            $os = 'Windows';
                                            $osIcon = 'fa-windows';
                                        } else if (strpos($userAgent, 'mac os') !== false) {
                                            $os = 'macOS';
                                            $osIcon = 'fa-apple';
                                        } else if (strpos($userAgent, 'linux') !== false) {
                                            $os = 'Linux';
                                            $osIcon = 'fa-linux';
                                        } else if (strpos($userAgent, 'android') !== false) {
                                            $os = 'Android';
                                            $osIcon = 'fa-android';
                                        } else if (strpos($userAgent, 'ios') !== false || 
                                                 strpos($userAgent, 'iphone') !== false || 
                                                 strpos($userAgent, 'ipad') !== false) {
                                            $os = 'iOS';
                                            $osIcon = 'fa-apple';
                                        }
                                    ?>
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas <?= $deviceIcon ?> text-blue-500 mr-2"></i>
                                            <span class="font-medium"><?= $deviceType ?></span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fab <?= $browserIcon ?> text-gray-400 mr-2"></i>
                                            <span><?= $browser ?></span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500">
                                            <i class="fab <?= $osIcon ?> text-gray-400 mr-2"></i>
                                            <span><?= $os ?></span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1 cursor-help" title="<?= esc($log['user_agent']); ?>">
                                            <i class="fas fa-info-circle mr-1"></i>Detail lengkap
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($log['status'] == 'success'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Berhasil
                                    </span>
                                <?php elseif($log['status'] == 'logout'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Gagal
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $activityClass ?>">
                                        <i class="fas <?= $activityIcon ?> mr-1"></i>
                                        <?= $activityStatus ?>
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">
                                        <?php if ($isOnline): ?>
                                            <i class="fas fa-clock text-green-500 mr-1"></i> <?= $lastSeen ?>
                                        <?php else: ?>
                                            <i class="fas fa-clock text-gray-400 mr-1"></i> Terakhir: <?= $lastSeen ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loginHistoryTable').DataTable({
        order: [[1, 'desc']], // Sort by login time descending
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        },
        dom: '<"flex items-center justify-between mb-4"lf>rtip',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        pageLength: 10,
        initComplete: function() {
            // Styling the search input
            $('.dataTables_filter input').addClass('border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
            
            // Styling the length select
            $('.dataTables_length select').addClass('border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500');
        }
    });
});
</script>

<style>
/* Custom styling for DataTables */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 1rem;
    margin-left: 0.5rem;
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    background-color: #ffffff;
    color: #374151;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #ffffff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #9ca3af;
    border-color: #e5e7eb;
    background-color: #f9fafb;
}
</style>
<?= $this->endSection(); ?> 