# Alur Action Dashboard Oasis Hotel

## Aturan booking guest

Guest wajib memiliki nomor identitas KTP/paspor, nomor telepon aktif, dan alamat lengkap
sebelum dapat memesan kamar.

Pemeriksaan dilakukan di server sebelum pengecekan/pembuatan reservasi. UI hanya
memberi petunjuk; request manual tetap ditolak jika profil belum lengkap. Relasi
reservasi memakai ID aktual dari tabel `guests`, bukan mengasumsikan ID tersebut sama
dengan ID pada tabel `users`.

## Hak action per role

| Area | Admin | Manager | Receptionist | Guest |
| --- | --- | --- | --- | --- |
| Restaurant & Gastronomy | Lihat detail dan ubah status | Lihat detail | Sesuai modul front desk | Pesan dan lihat pesanan sendiri |
| Facilities & Wellness | Lihat detail, ubah status, CRUD fasilitas | Lihat detail | Sesuai modul front desk | Pesan dan lihat fasilitas |
| Booking kamar | Kelola reservasi | Audit/read-only | Proses front desk | Membuat booking setelah profil lengkap |

Ikon mata selalu merupakan action baca detail. Action yang mengubah atau menghapus data
menampilkan dialog konfirmasi bertema Oasis. Pesan sukses, error validasi, dan kegagalan
AJAX menggunakan dialog yang sama pada dashboard Admin, Manager, Receptionist, dan Guest.

## Pemeriksaan cepat

```bash
npm run build
docker compose exec -T web1 php artisan optimize:clear
docker compose exec -T web1 php artisan optimize
```

Jika tampilan dialog atau style belum berubah setelah deploy, rebuild image dengan
`docker compose up -d --build --wait` dan pastikan browser tidak lagi memuat URL Vite
development dari `public/hot`.
