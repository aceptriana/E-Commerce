                                    <td><?= $detail['nama_produk'] ?></td>
                                    <td><?= $detail['jumlah'] ?></td>
                                    <td>Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($detail['jumlah'] * $detail['harga'], 0, ',', '.') ?></td> 