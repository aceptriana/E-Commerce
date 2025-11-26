<?= view('layouts/home/header'); ?>

<style>
/* Enhanced Loading Styles */
/* Spinner animation should be handled by Bootstrap's spinner; avoid animating text */
.loading-spinner { }

/* Friendly Dot Loader */
.dot-loader { display: inline-flex; align-items: center; gap: 6px; }
.dot-loader .dot { width: 8px; height: 8px; background-color: currentColor; border-radius: 50%; opacity: 0.9; animation: dotBounce 1s infinite ease-in-out; }
.dot-loader .dot:nth-child(2) { animation-delay: 0.12s; }
.dot-loader .dot:nth-child(3) { animation-delay: 0.24s; }
.dot-loader-lg .dot { width: 18px; height: 18px; }

@keyframes dotBounce {
    0%, 80%, 100% { transform: translateY(0); opacity: 0.6; }
    40% { transform: translateY(-6px); opacity: 1; }
}

/* Skeleton Loader */
.skeleton { background: linear-gradient(90deg, #eee 25%, #f5f5f5 37%, #eee 63%); background-size: 400% 100%; animation: shimmer 1.2s linear infinite; }
.skeleton-line { height: 12px; border-radius: 6px; margin-bottom: 8px; }
.skeleton-card { padding: 12px; border-radius: 8px; }

@keyframes shimmer {
    0% { background-position: -400% 0; }
    100% { background-position: 400% 0; }
}

/* Reduced motion for users who prefer it */
@media (prefers-reduced-motion: reduce) {
    .dot-loader .dot, .skeleton { animation: none; }
}

/* City Suggestions Enhanced */
#city-suggestions .list-group-item {
    transition: all 0.2s ease;
    border: none;
    border-bottom: 1px solid #eee;
}

#city-suggestions .list-group-item:hover {
    background-color: #f8f9fa !important;
    transform: translateX(2px);
}

#city-suggestions .list-group-item:last-child {
    border-bottom: none;
}

/* City Search Loading: match suggestion styling and appearance */
#city-search-loading { display: none; }

/* Button Loading States */
.btn_1[disabled], .btn_1.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Progress Bar Animation */
.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

/* Alert Animations */
.custom-alert {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
                    /* keep keyframes pure CSS */
}

/* Loading Overlay */
#loading-overlay {
    backdrop-filter: blur(2px);
}

/* Enhanced Form Controls */
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Shipping Options Animation */
#shipping-options-container {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Error States */
.alert-danger {
    border-left: 4px solid #dc3545;
}

.alert-success {
    border-left: 4px solid #28a745;
}

.alert-warning {
    border-left: 4px solid #ffc107;
                                    /* ensure aria-busy will be toggled via JS */

.alert-info {
    border-left: 4px solid #17a2b8;
}
</style>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Checkout</li>
                </ul>
            </div>
            <h1>Checkout</h1>
        </div>
        <!-- /page_header -->

        <div class="row">
            <!-- 1. Informasi Pengiriman -->
            <div class="col-lg-4 col-md-6">
                <div class="step first">
                    <h3>1. Informasi Pengiriman</h3>
                    <form id="shipping-form">
                        <input type="hidden" id="selected_cart_ids" name="selected_cart_ids" value="<?= $selected_cart_ids ?? '' ?>">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="tel" class="form-control" id="no_telepon" name="no_telepon" value="<?= $user['no_telepon'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= $user['alamat'] ?? '' ?></textarea>
                        </div>
                    </form>
                </div>
                <!-- /step -->
            </div>

            <!-- 2. Opsi Pengiriman -->
            <div class="col-lg-4 col-md-6">
                <div class="step middle">
                    <h3>2. Opsi Pengiriman</h3>
                    
                    <!-- Kota Tujuan dipindah ke sini -->
                    <div class="form-group">
                        <label>Kota Tujuan</label>
                        <div style="position: relative;">
                            <div class="input-group">
                                <input type="text" class="form-control" id="city-input" name="city_input" placeholder="Ketik nama kota..." required aria-busy="false">
                                <div class="input-group-append">
                                    <div class="dot-loader text-primary" id="city-loading" role="status" style="display:none;">
                                        <span class="dot" aria-hidden="true"></span>
                                        <span class="dot" aria-hidden="true"></span>
                                        <span class="dot" aria-hidden="true"></span>
                                        <span class="sr-only">Mencari Kota...</span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="destination" name="destination">
                            
                            <!-- Enhanced City Suggestions -->
                            <div id="city-suggestions" class="list-group" style="position: absolute; z-index: 1000; width: 100%; max-height: 300px; overflow-y: auto; display: none; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            </div>
                            
                            <!-- City Search Loading Overlay -->
                            <div id="city-search-loading" class="d-none" role="status" aria-live="polite" aria-atomic="true" style="position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 1001; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 16px; text-align: center;">
                                <div class="card border-0 shadow-sm" style="margin: 0;">
                                    <div class="card-body" style="padding: 12px;">
                                        <div class="d-flex flex-column align-items-center">
                                                    <div class="dot-loader dot-loader-lg text-primary mb-2" role="status" aria-hidden="true" style="--dot-color: #0d6efd;">
                                                        <span class="dot" aria-hidden="true"></span>
                                                        <span class="dot" aria-hidden="true"></span>
                                                        <span class="dot" aria-hidden="true"></span>
                                                    </div>
                                                    <strong class="text-primary mb-1">Mencari Kota...</strong>
                                                    <div class="text-muted small">Sedang mencari kota. Mohon tunggu...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn_1 full-width" id="check-shipping-btn" onclick="checkShipping()">
                        <span id="btn-text">Cek Biaya Pengiriman</span>
                            <span id="btn-loading" style="display: none;">
                                <span class="dot-loader text-white" role="status" aria-hidden="true">
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                </span>
                                Memproses...
                            </span>
                    </button>
                    
                    <div id="shipping-options-container" style="display:none;" class="mt-3">
                        <div class="form-group">
                            <label for="shipping-services">Pilih Layanan</label>
                            <select id="shipping-services" class="form-control" name="shipping_service">
                                <!-- Options will be populated by AJAX -->
                            </select>
                        </div>
                        <div class="shipping-details mt-3">
                            <p><strong>Biaya:</strong> <span id="shipping-cost">Rp 0</span></p>
                            <p><strong>Estimasi:</strong> <span id="shipping-etd">-</span></p>
                            <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
                            <input type="hidden" id="shipping_service" name="shipping_service" value="">
                            <input type="hidden" id="shipping_description" name="shipping_description" value="">
                        </div>
                    </div>
                    <!-- Enhanced Loading for Shipping -->
                    <div id="shipping-loading" style="display:none;" class="mt-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-4">
                                        <div class="loading-spinner mb-3">
                                            <div class="skeleton-card w-100" aria-hidden="true">
                                                <div class="skeleton skeleton-line" style="height: 18px; width: 60%; margin: 8px auto 12px;"></div>
                                                <div class="skeleton skeleton-line" style="height: 12px; width: 80%; margin: 6px auto;"></div>
                                            </div>
                                        </div>
                                <h6 class="text-primary mb-2">Mencari Opsi Pengiriman</h6>
                                <p class="text-muted mb-0">Sedang menghubungi layanan pengiriman...</p>
                                <div class="progress mt-3" style="height: 4px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Error Display -->
                    <div id="shipping-error" class="alert alert-danger mt-3" style="display:none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Gagal Memuat Opsi Pengiriman</strong>
                                <div class="error-message mt-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /step -->
            </div>

            <!-- 3. Ringkasan Pesanan -->
            <div class="col-lg-4 col-md-6">
                <div class="step last">
                    <h3>3. Ringkasan Pesanan</h3>
                    <div class="box_general summary">
                        <ul>
                            <?php foreach($cart_items as $item): ?>
                            <li class="clearfix">
                                <em><?= $item['quantity'] ?>x <?= $item['produk_nama'] ?></em>
                                <span>Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <ul>
                            <li class="clearfix"><em><strong>Subtotal</strong></em> <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span></li>
                            <li class="clearfix"><em><strong>Pengiriman</strong></em> <span id="display-shipping-cost">Rp 0</span></li>
                        </ul>
                        <div class="total clearfix">TOTAL <span id="display-total">Rp <?= number_format($subtotal, 0, ',', '.') ?></span></div>
                        <button type="button" id="pay-button" class="btn_1 full-width" disabled>
                            <span id="pay-btn-text">Bayar Sekarang</span>
                            <span id="pay-btn-loading" style="display: none;">
                                <span class="dot-loader text-white me-2" role="status" aria-hidden="true">
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                    <span class="dot" style="width:6px;height:6px;background-color:currentColor;opacity:0.9"></span>
                                </span>
                                Memproses Pembayaran...
                            </span>
                        </button>
                    </div>
                    <!-- /box_general -->
                </div>
                <!-- /step -->
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
<!--/main-->

<?= view('layouts/home/footer'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $midtrans_client_key ?>"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - Checkout page loaded');
    
    const cityInput = $('#city-input');
    const citySuggestions = $('#city-suggestions');
    const destinationInput = $('#destination');
    let typingTimer;
    const doneTypingInterval = 500;

    // City input handler with debounce
    cityInput.on('input', function() {
        clearTimeout(typingTimer);
        const query = $(this).val();
        
        if (query.length < 2) {
            citySuggestions.hide();
            $('#city-search-loading').addClass('d-none');
            $('#city-loading').hide();
            cityInput.prop('disabled', false).attr('aria-busy', 'false');
            return;
        }

        // Show loading indicators
        $('#city-loading').show();
        $('#city-search-loading').removeClass('d-none');
            cityInput.attr('aria-busy', 'true');
        citySuggestions.hide();

        typingTimer = setTimeout(function() {
            console.log('Searching for city:', query);
            $.ajax({
                url: '<?= base_url('checkout/searchCity') ?>',
                type: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function(data) {
                    console.log('Received city data:', data);
                    
                    // Hide loading indicators
                    $('#city-loading').hide();
                    $('#city-search-loading').addClass('d-none');
                    cityInput.prop('disabled', false).attr('aria-busy', 'false');
                    
                    citySuggestions.empty();
                    
                    // Check if response contains error
                    if (data.error) {
                        console.error('API Error:', data.error);
                        const errorItem = $('<div>')
                            .addClass('list-group-item')
                            .css({
                                'color': '#dc3545',
                                'padding': '12px 15px',
                                'font-style': 'italic',
                                'border-left': '3px solid #dc3545'
                            })
                            .html('<i class="fas fa-exclamation-triangle me-2"></i>Error: ' + data.error);
                        citySuggestions.append(errorItem);
                        citySuggestions.show();
                        return;
                    }
                    
                    if (data.length > 0) {
                        data.forEach(city => {
                            const item = $('<a>')
                                .addClass('list-group-item list-group-item-action')
                                .css({
                                    'cursor': 'pointer',
                                    'padding': '12px 15px',
                                    'border-bottom': '1px solid #eee',
                                    'transition': 'all 0.2s ease'
                                })
                                .html(`
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <div>
                                            <strong>${city.type} ${city.city_name}</strong>
                                            <div class="text-muted small">${city.province}</div>
                                        </div>
                                    </div>
                                `)
                                .on('click', function(e) {
                                    e.preventDefault();
                                    console.log('City selected:', city);
                                    cityInput.val(city.type + ' ' + city.city_name);
                                    destinationInput.val(city.city_id);
                                    citySuggestions.hide();
                                    $('#city-loading').hide();
                                    $('#city-search-loading').addClass('d-none');
                                    cityInput.prop('disabled', false).attr('aria-busy', 'false');
                                    
                                    // Reset shipping options when city changes
                                    $('#shipping-options-container').hide();
                                    $('#shipping-services').html('<option value="">Pilih Layanan</option>');
                                    resetShippingCost();
                                })
                                .on('mouseenter', function() {
                                    $(this).css('background-color', '#f8f9fa');
                                })
                                .on('mouseleave', function() {
                                    $(this).css('background-color', 'white');
                                });
                            citySuggestions.append(item);
                        });
                        citySuggestions.show();
                    } else {
                        const noResultsItem = $('<div>')
                            .addClass('list-group-item')
                            .css({
                                'color': '#6c757d',
                                'padding': '12px 15px',
                                'font-style': 'italic',
                                'text-align': 'center'
                            })
                            .html('<i class="fas fa-search me-2"></i>Tidak ada kota yang ditemukan');
                        citySuggestions.append(noResultsItem);
                        citySuggestions.show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error searching cities:', error);
                    console.error('Response:', xhr.responseText);
                    
                    // Hide loading indicators
                    $('#city-loading').hide();
                    $('#city-search-loading').addClass('d-none');
                    cityInput.prop('disabled', false).attr('aria-busy', 'false');
                    
                    const errorItem = $('<div>')
                        .addClass('list-group-item')
                        .css({
                            'color': '#dc3545',
                            'padding': '12px 15px',
                            'font-style': 'italic',
                            'border-left': '3px solid #dc3545'
                        })
                        .html('<i class="fas fa-exclamation-triangle me-2"></i>Error: Gagal mencari kota');
                    citySuggestions.empty().append(errorItem).show();
                }
            });
        }, doneTypingInterval);
    });

    // Hide suggestions when clicking outside
    $(document).on('click', function(e) {
        if (!cityInput.is(e.target) && !citySuggestions.is(e.target) && !citySuggestions.has(e.target).length) {
            citySuggestions.hide();
            $('#city-search-loading').addClass('d-none');
            $('#city-loading').hide();
            cityInput.prop('disabled', false).attr('aria-busy', 'false');
                    cityInput.attr('aria-busy', 'false');
        }
    });

    // Update shipping cost when service is selected
    $('#shipping-services').on('change', function() {
        const selected = $(this).find('option:selected');
        const cost = parseInt(selected.val()) || 0;
        const etd = selected.data('etd') || '-';
        const description = selected.data('description') || '';

        $('#shipping-cost').text('Rp ' + cost.toLocaleString('id-ID'));
        $('#shipping-etd').text(etd + ' hari');
        $('#shipping_cost').val(cost);
        $('#shipping_service').val(description);
        $('#shipping_description').val(description);

        // Update total
        const subtotal = <?= $subtotal ?>;
        const total = subtotal + cost;
        $('#display-shipping-cost').text('Rp ' + cost.toLocaleString('id-ID'));
        $('#display-total').text('Rp ' + total.toLocaleString('id-ID'));

        // Enable pay button if shipping is selected
        $('#pay-button').prop('disabled', !cost);
    });

    // Process payment
    $('#pay-button').on('click', function() {
        const formData = new FormData(document.getElementById('shipping-form'));
        
        // Add shipping details
        formData.append('destination', $('#destination').val());
        formData.append('shipping_cost', $('#shipping_cost').val());
        formData.append('shipping_service', $('#shipping_service').val());
        formData.append('shipping_description', $('#shipping_description').val());
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        // Validate required fields
        if (!$('#destination').val()) {
            showAlert('Silakan pilih kota tujuan', 'warning');
            return;
        }
        
        if (!$('#shipping_cost').val() || $('#shipping_cost').val() == '0') {
            showAlert('Silakan pilih layanan pengiriman', 'warning');
            return;
        }

        // Show loading state
        $('#pay-button').prop('disabled', true);
        $('#pay-btn-text').hide();
        $('#pay-btn-loading').show();

        $.ajax({
            url: '<?= base_url('checkout/process') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                // Hide loading state
                $('#pay-button').prop('disabled', false);
                $('#pay-btn-text').show();
                $('#pay-btn-loading').hide();
                
                if (response.status === 'success') {
                    snap.pay(response.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '<?= base_url('checkout/success') ?>';
                        },
                        onPending: function(result) {
                            window.location.href = '<?= base_url('checkout/pending') ?>';
                        },
                        onError: function(result) {
                            showAlert('Pembayaran gagal', 'error');
                        },
                        onClose: function() {
                            showAlert('Anda menutup popup tanpa menyelesaikan pembayaran', 'info');
                        }
                    });
                } else {
                    showAlert(response.message || 'Gagal memproses pembayaran', 'error');
                }
            },
            error: function() {
                // Hide loading state
                $('#pay-button').prop('disabled', false);
                $('#pay-btn-text').show();
                $('#pay-btn-loading').hide();
                
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
            }
        });
    });
});

// Function to check shipping cost - PERBAIKAN URL
function checkShipping() {
    const destination = $('#destination').val();
    const weight = <?= $total_weight ?>;

    if (!destination) {
        // Show user-friendly alert
        showAlert('Silakan pilih kota tujuan terlebih dahulu', 'warning');
        return;
    }

    // Show loading states
    $('#shipping-loading').show();
    $('#shipping-options-container').hide();
    $('#shipping-error').hide();
    
    // Update button state
    $('#check-shipping-btn').prop('disabled', true);
    $('#btn-text').hide();
    $('#btn-loading').show();

    $.ajax({
        url: '<?= base_url('checkout/cek-ongkir') ?>', // DIPERBAIKI: konsisten dengan routes
        type: 'POST',
        data: {
            destination: destination,
            weight: weight,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            // Hide loading states
            $('#shipping-loading').hide();
            $('#check-shipping-btn').prop('disabled', false);
            $('#btn-text').show();
            $('#btn-loading').hide();
            
            if (response.success) {
                let html = '<option value="">Pilih Layanan</option>';
                response.data.forEach(courier => {
                    courier.costs.forEach(cost => {
                        cost.cost.forEach(service => {
                            html += `<option value="${service.value}" 
                                data-etd="${service.etd}"
                                data-description="${courier.code.toUpperCase()} - ${cost.description}">
                                ${courier.code.toUpperCase()} - ${cost.description} (Rp ${service.value.toLocaleString('id-ID')})
                            </option>`;
                        });
                    });
                });
                $('#shipping-services').html(html);
                $('#shipping-options-container').show();
                
                // Show success message
                showAlert('Opsi pengiriman berhasil dimuat!', 'success');
            } else {
                $('#shipping-error .error-message').text(response.message);
                $('#shipping-error').show();
            }
        },
        error: function(xhr, status, error) {
            // Hide loading states
            $('#shipping-loading').hide();
            $('#check-shipping-btn').prop('disabled', false);
            $('#btn-text').show();
            $('#btn-loading').hide();
            
            let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
            
            // Tampilkan pesan error yang lebih detail
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 404) {
                errorMessage = 'Layanan tidak ditemukan. Periksa konfigurasi route.';
            }
            
            $('#shipping-error .error-message').text(errorMessage);
            $('#shipping-error').show();
            
            console.error('Error details:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
        }
    });
}

// Function to reset shipping cost
function resetShippingCost() {
    $('#shipping-cost').text('Rp 0');
    $('#shipping-etd').text('-');
    $('#shipping_cost').val('0');
    $('#shipping_service').val('');
    $('#shipping_description').val('');
    
    const subtotal = <?= $subtotal ?>;
    $('#display-shipping-cost').text('Rp 0');
    $('#display-total').text('Rp ' + subtotal.toLocaleString('id-ID'));
    $('#pay-button').prop('disabled', true);
}

// Utility function untuk menampilkan alert yang user-friendly
function showAlert(message, type = 'info') {
    // Remove existing alerts
    $('.custom-alert').remove();
    
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const iconClass = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} custom-alert alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <div class="d-flex align-items-center">
                <i class="${iconClass} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        $('.custom-alert').fadeOut(300, function() {
            $(this).remove();
        });
    }, 5000);
}

// Enhanced loading animation
function showLoadingOverlay(message = 'Memproses...') {
    if ($('#loading-overlay').length === 0) {
        const overlayHtml = `
            <div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                <div class="card border-0 shadow-lg" style="max-width: 300px;">
                    <div class="card-body text-center py-4" role="status" aria-live="polite">
                        <div class="dot-loader dot-loader-lg text-white mb-3" role="status" aria-hidden="true">
                            <span class="dot" aria-hidden="true" style="background-color: white;"></span>
                            <span class="dot" aria-hidden="true" style="background-color: white;"></span>
                            <span class="dot" aria-hidden="true" style="background-color: white;"></span>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <h6 class="text-primary mb-2">${message}</h6>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(overlayHtml);
    } else {
        $('#loading-overlay').show();
    }
}

function hideLoadingOverlay() {
    $('#loading-overlay').fadeOut(300, function() {
        $(this).remove();
    });
}
</script>