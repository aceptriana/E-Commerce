<?= view('layouts/home/header'); ?>

<main class="bg_gray">
    <div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li>Keranjang</li>
                </ul>
            </div>
            <h1>Keranjang Belanja</h1>
        </div>
        <!-- /page_header -->
        
        <?php if(empty($cart_items)): ?>
            <div class="text-center">
                <h3>Keranjang belanja Anda kosong</h3>
                <p>Silakan pilih produk yang ingin Anda beli</p>
                <a href="<?= base_url() ?>" class="btn_1">Lanjut Belanja</a>
            </div>
        <?php else: ?>
            <table class="table table-striped cart-list">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cart_items as $item): ?>
                    <tr>
                        <td>
                            <div class="thumb_cart">
                                <img src="<?= base_url($item['gambar'] ?? 'img/products/product_placeholder_square_small.jpg') ?>" class="lazy" alt="<?= $item['produk_nama'] ?>" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                            <span class="item_cart"><?= $item['produk_nama'] ?></span>
                        </td>
                        <td>
                            <strong>Rp <?= number_format($item['harga'], 0, ',', '.') ?></strong>
                        </td>
                        <td>
                            <div class="numbers-row">
                                <input type="number" value="<?= $item['quantity'] ?>" id="quantity_<?= $item['id'] ?>" class="qty2" name="quantity_<?= $item['id'] ?>" min="1">
                                <div class="inc button_inc">+</div>
                                <div class="dec button_inc">-</div>
                            </div>
                        </td>
                        <td>
                            <strong>Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></strong>
                        </td>
                        <td class="options">
                            <a href="javascript:void(0)" onclick="removeFromCart(<?= $item['id'] ?>)"><i class="ti-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="row add_top_30 flex-sm-row-reverse cart_actions">
                <div class="col-sm-4 text-end">
                    <button type="button" class="btn_1 gray" onclick="updateCart()">Update Cart</button>
                    <a href="<?= base_url('checkout') ?>" class="btn_1 full-width cart">Lanjut ke Pembayaran</a>
                </div>
            </div>
            <!-- /cart_actions -->
        <?php endif; ?>
    </div>
    <!-- /container -->
</main>
<!--/main-->

<?= view('layouts/home/footer'); ?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle increment button
    document.querySelectorAll('.inc').forEach(function(button) {
        button.addEventListener('click', function() {
            var input = this.parentElement.querySelector('input');
            var value = parseInt(input.value);
            input.value = value + 1;
        });
    });

    // Handle decrement button
    document.querySelectorAll('.dec').forEach(function(button) {
        button.addEventListener('click', function() {
            var input = this.parentElement.querySelector('input');
            var value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
            }
        });
    });
});

// Make functions globally available
window.updateCart = function() {
    const items = [];
    document.querySelectorAll('.cart-list tbody tr').forEach(function(row) {
        const input = row.querySelector('input[type="number"]');
        const id = input.id.replace('quantity_', '');
        const quantity = parseInt(input.value);
        if (!isNaN(quantity) && quantity > 0) {
            items.push({
                id: id,
                quantity: quantity
            });
        }
    });

    if (items.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Tidak ada data untuk diperbarui'
        });
        return;
    }

    // Show loading state
    Swal.fire({
        title: 'Memperbarui Keranjang',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Debug log
    console.log('Sending items:', items);

    // Send data as form data
    const formData = new FormData();
    formData.append('items', JSON.stringify(items));

    // Debug log
    console.log('FormData items:', formData.get('items'));

    fetch('<?= base_url('cart/update') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        console.log('Server response:', text);
        
        if (!response.ok) {
            throw new Error(`Server error: ${text}`);
        }
        
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error('Invalid JSON response from server');
        }
    })
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Keranjang berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Terjadi kesalahan saat memperbarui keranjang'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
        });
    });
};

window.removeFromCart = function(cart_id) {
    Swal.fire({
        title: 'Hapus Item?',
        text: "Apakah Anda yakin ingin menghapus item ini dari keranjang?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Menghapus Item',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('<?= base_url('cart/remove/') ?>' + cart_id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Item berhasil dihapus dari keranjang',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message || 'Terjadi kesalahan saat menghapus item'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.'
                });
            });
        }
    });
};
</script> 