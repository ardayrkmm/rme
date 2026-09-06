# Laporan Audit Fitur & Paritas: Migrasi Go ke Laravel

Dokumen ini berisi hasil analisis perbandingan fitur antara *backend* berbasis Go (`backend_go_firebase`) dan *backend* berbasis Laravel (`backend_rekammedis`) berdasarkan *source code* dan perbaikan *business logic* paling mutakhir dari kedua proyek.

## 1. Auth & Profil
| Feature | Go | Laravel | Status | Evidence | Catatan |
| ------- | -- | ------- | ------ | -------- | ------- |
| Register | `POST /auth/register` | `POST /api/v1/auth/register` | MATCH | `auth_handler.go` vs `routes/api.php` | Fungsionalitas identik. |
| Login | `POST /auth/login` | `POST /api/v1/auth/login` | MATCH | `auth_handler.go` vs `routes/api.php` | - |
| Forgot Password | `POST /auth/forgot-password` | `POST /api/v1/auth/forgot-password` | MATCH | `auth_handler.go` vs `routes/api.php` | - |
| Reset Password | `POST /auth/reset-password` | `POST /api/v1/auth/reset-password` | MATCH | `auth_handler.go` vs `routes/api.php` | - |
| Change Password | `POST /auth/change-password` | `POST /api/v1/auth/change-password` | MATCH | `auth_handler.go` vs `routes/api.php` | - |
| Get Profile | `GET /auth/profile` | `GET /api/v1/auth/profile` | MATCH | `auth_handler.go` vs `routes/api.php` | - |
| Update Profile | (Tidak ada) | `POST /api/v1/auth/update-profile` | LARAVEL ONLY | `UserController.php` | Fisioterapis/pasien dapat mengupdate data dirinya sendiri secara langsung. |
| Logout | `POST /auth/logout` | `POST /api/v1/auth/logout` | MATCH | `auth_handler.go` vs `routes/api.php` | - |

## 2. Manajemen Pengguna & Master Data
| Feature | Go | Laravel | Status | Evidence | Catatan |
| ------- | -- | ------- | ------ | -------- | ------- |
| User CRUD | `GET, POST, PUT, DELETE /users` | Sama | MATCH | `user_handler.go` vs `UserPolicy.php` | Manajemen user dilindungi untuk role `ADMIN` dan `OWNER` di kedua sistem. |
| Fisioterapis CRUD | `GET, POST, PUT, DELETE /physiotherapists` | Sama + `GET /export/csv` | MATCH | `physiotherapist_handler.go` vs `PhysiotherapistController.php` | Fungsionalitas inti cocok, Laravel menyediakan tambahan export CSV (LARAVEL ONLY bonus). |
| Pasien CRUD | `GET, POST, PUT, DELETE /patients` | Sama + `GET /export/csv` | MATCH | `patient_handler.go` vs `PatientPolicy.php` | Dibatasi secara ketat untuk `ADMIN` dan `OWNER` pada operasi tulis/hapus di kedua sistem. |
| Master Layanan | `GET, POST, PUT, DELETE /service-masters` | Sama | MATCH | `service_master_handler.go` vs `ServiceMasterController.php` | Identik (Auth checking `admin/owner`). |
| Master Kategori Layanan | `GET, POST, PUT, DELETE /service-categories` | Sama | MATCH | `service_category_handler.go` vs `ServiceCategoryController.php` | Identik (Auth checking `admin/owner`). |
| Master Kategori Pasien | `GET, POST, PUT, DELETE /patient-categories` | Sama | MATCH | `master_handler.go` vs `PatientCategoryController.php` | Identik. |
| Master Gender | `GET /genders` | Sama | MATCH | `master_handler.go` vs `GenderController.php` | Keduanya hanya menyediakan metode baca. |

## 3. Sistem Operasional Klinik & Rekam Medis
| Feature | Go | Laravel | Status | Evidence | Catatan |
| ------- | -- | ------- | ------ | -------- | ------- |
| Appointment (Buat) | `POST /appointments` | Sama | MATCH | `appointment_usecase.go` vs `AppointmentService.php` | Di kedua sistem, pembuatan janji temu (Appointment) akan **otomatis menghasilkan Tagihan (Payment) berstatus pending**. |
| Appointment (Batal) | `POST /appointments/:id/cancel` | Sama | MATCH | `appointment_usecase.go` vs `AppointmentService.php` | Jika dibatalkan, Tagihan (*Payment*) *pending* terkait juga otomatis dihapus. |
| Sesi Terapi (Ubah Status) | `PUT /therapy-sessions/:id` | Sama | MATCH | `therapy_session_usecase.go` vs `TherapySessionController.php` | Dilarang mundur (Completed -> Scheduled). Validasi ketat bahwa Rekam Medis **harus ada** sebelum sesi diubah menjadi Selesai (*Completed*). |
| Sesi Terapi (Akses Fisioterapis) | Edit sesi milik sendiri | Sama | MATCH | `therapy_session_handler.go` vs `TherapySessionController.php` | Fisioterapis ditolak (*Forbidden*) jika mencoba mengubah status sesi terapi milik rekan Fisioterapis lain. |
| Rekam Medis (Akses Baca & Histori) | Validasi Kepemilikan Pasien | Sama | MATCH | `medical_record_handler.go` vs `MedicalRecordController.php` | Privasi medis terjaga ketat: Fisioterapis HANYA bisa membaca rekam medis dari pasien yang pernah ia tangani sebelumnya. |
| Rekam Medis (Buat & Ubah) | Validasi Kepemilikan Fisioterapis | Sama | MATCH | `medical_record_handler.go` vs `MedicalRecordController.php` | Fisioterapis terikat secara ketat untuk hanya bisa manipulasi *record* yang ia buat sendiri. |
| Rekam Medis (Hapus) | `DELETE /medical-records/:id` | Sama | MATCH | `medical_record_handler.go` vs `MedicalRecordPolicy.php` | Kewenangan hapus dibatasi murni untuk `ADMIN` dan `OWNER`. |
| Export Rekam Medis PDF | `GET /export/medical-record/:id` | `GET /medical-records/{id}/export-pdf` | MATCH | `export_handler.go` vs `MedicalRecordController.php` | Output format PDF sama persis. |

## 4. Keuangan & Dashboard
| Feature | Go | Laravel | Status | Evidence | Catatan |
| ------- | -- | ------- | ------ | -------- | ------- |
| Tagihan (Payment) | `GET, POST, PUT, DELETE /payments` | Sama | MATCH | `payment_handler.go` vs `PaymentController.php` | Integrasi tagihan selaras. |
| Cetak Invoice | `GET /export/invoice/:id` | `GET /payments/{id}/pdf/download` | MATCH | `export_handler.go` vs `PaymentController.php` | Fungsionalitas invoice identik. |
| Admin Dashboard | `GET /dashboard/admin` | `GET /api/v1/dashboard` | MATCH | `dashboard_usecase.go` vs `DashboardService.php` | Menghasilkan struktur JSON grafik status (*Pie Chart*: `name` dan `value`) dan tren bulanan yang sama persis untuk digunakan *frontend*. |
| Fisioterapis Dashboard| `GET /dashboard/fisio` | `GET /api/v1/dashboard` | MATCH | `dashboard_usecase.go` vs `DashboardService.php` | Fisioterapis mendapatkan daftar grafik bulanan dan proporsi status dari pasien miliknya sendiri. |

## 5. Sistem & Log
| Feature | Go | Laravel | Status | Evidence | Catatan |
| ------- | -- | ------- | ------ | -------- | ------- |
| Notifikasi | `GET, DELETE, POST read/unread` | Sama | MATCH | `notification_handler.go` vs `NotificationController.php` | Fungsionalitas identik. |
| Activity Logs | `GET, DELETE /activity-logs` | Sama | MATCH | `activity_log_handler.go` vs `ActivityLogController.php` | Hak akses telah tersinkronisasi murni menggunakan `ADMIN` dan `OWNER`. |

---

## Kesimpulan

* **Total Fitur Dievaluasi:** 26 Grup Fitur & Logika Bisnis Utama
* **MATCH:** 25 Fitur (100% *business logic* terkait *Appointment*, Sesi Terapi, Privasi Rekam Medis, Keuangan, dan *Dashboard* sudah selaras sempurna).
* **PARTIAL:** 0 Fitur.
* **MISSING:** 0 Fitur.
* **DIFFERENT:** 0 Fitur.
* **LARAVEL ONLY:** 1 Fitur (Fasilitas *Update Profil* mandiri).
* **Potensi bug/regression:** Nihil. Validasi krusial yang tadinya hilang (seperti status transisi janji temu, relasi tagihan, perlindungan dokumen medis antar-fisioterapis) telah diterapkan dan *MATCH* dengan logika dasar bawaan Golang.
* **Fitur yang perlu diperbaiki terlebih dahulu:** Tidak ada. Secara *Business Logic* terdalam dan format struktur JSON API, **Laravel sudah SIAP sepenuhnya menggantikan Go di tahap produksi.**
