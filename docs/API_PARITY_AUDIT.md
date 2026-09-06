# API PARITY AUDIT — GO vs LARAVEL

Dokumen ini berisi hasil audit komprehensif yang membandingkan *backend* Go (`backend_go_firebase`) dengan *backend* Laravel (`backend_rekammedis`). Audit dilakukan sampai ke level *business logic*, basis data, dan filter, mengacu pada kondisi *source code* terkini tanpa ada asumsi.

## Daftar Endpoint & Status

| Method | Go Endpoint | Laravel Endpoint | Status | Evidence | Difference |
| ------ | ----------- | ---------------- | ------ | -------- | ---------- |
| POST | `/auth/register` | `/api/v1/auth/register` | MATCH | `auth_handler.go` / `api.php` | - |
| POST | `/auth/login` | `/api/v1/auth/login` | MATCH | `auth_handler.go` / `api.php` | - |
| POST | `/auth/forgot-password` | `/api/v1/auth/forgot-password` | MATCH | `auth_handler.go` / `api.php` | - |
| POST | `/auth/reset-password` | `/api/v1/auth/reset-password` | MATCH | `auth_handler.go` / `api.php` | - |
| POST | `/auth/change-password` | `/api/v1/auth/change-password` | MATCH | `auth_handler.go` / `api.php` | - |
| GET | `/auth/profile` | `/api/v1/auth/profile` | MATCH | `auth_handler.go` / `api.php` | - |
| POST | `/auth/logout` | `/api/v1/auth/logout` | MATCH | `auth_handler.go` / `api.php` | - |
| GET | `/users` | `/api/v1/users` | MATCH | `user_handler.go` / `UserController.php` | - |
| GET | `/users/:id` | `/api/v1/users/:id` | MATCH | `user_handler.go` / `UserController.php` | - |
| POST | `/users` | `/api/v1/users` | MATCH | `user_handler.go` / `UserController.php` | - |
| PUT | `/users/:id` | `/api/v1/users/:id` | MATCH | `user_handler.go` / `UserController.php` | - |
| DELETE | `/users/:id` | `/api/v1/users/:id` | MATCH | `user_handler.go` / `UserController.php` | - |
| GET | `/patients` | `/api/v1/patients` | MATCH | `patient_handler.go` / `PatientController.php` | - |
| POST | `/patients` | `/api/v1/patients` | MATCH | `patient_handler.go` / `PatientController.php` | - |
| PUT | `/patients/:id` | `/api/v1/patients/:id` | MATCH | `patient_handler.go` / `PatientController.php` | - |
| DELETE | `/patients/:id` | `/api/v1/patients/:id` | MATCH | `patient_handler.go` / `PatientController.php` | - |
| GET | `/physiotherapists` | `/api/v1/physiotherapists` | MATCH | `physio_handler.go` / `PhysioController.php` | - |
| POST | `/physiotherapists` | `/api/v1/physiotherapists` | MATCH | `physio_handler.go` / `PhysioController.php` | - |
| PUT | `/physiotherapists/:id` | `/api/v1/physiotherapists/:id` | MATCH | `physio_handler.go` / `PhysioController.php` | - |
| DELETE | `/physiotherapists/:id` | `/api/v1/physiotherapists/:id` | MATCH | `physio_handler.go` / `PhysioController.php` | - |
| GET | `/dashboard/admin` | `/api/v1/dashboard` (Admin) | MATCH | `dashboard_usecase.go` / `DashboardService.php` | - |
| GET | `/dashboard/fisio` | `/api/v1/dashboard` (Fisio) | MATCH | `dashboard_usecase.go` / `DashboardService.php` | - |
| GET | `/appointments` | `/api/v1/appointments` | MATCH | `appointment_usecase.go` / `AppointmentService.php`| - |
| POST | `/appointments` | `/api/v1/appointments` | MATCH | `appointment_usecase.go` / `AppointmentService.php`| - |
| PUT | `/appointments/:id` | `/api/v1/appointments/:id` | MATCH | `appointment_usecase.go` / `AppointmentService.php`| - |
| POST | `/appointments/:id/cancel`| `/api/v1/appointments/:id/cancel`| MATCH | `appointment_usecase.go` / `AppointmentService.php`| - |
| POST | `/appointments/:id/reschedule`| `/api/v1/appointments/:id/reschedule`| MATCH | `appointment_usecase.go` / `AppointmentService.php`| - |
| GET | `/therapy-sessions` | `/api/v1/therapy-sessions` | MATCH | `therapy_session_usecase.go` / `TherapySessionController.php`| - |
| POST | `/therapy-sessions` | `/api/v1/therapy-sessions` | MATCH | `therapy_session_usecase.go` / `TherapySessionController.php`| - |
| PUT | `/therapy-sessions/:id`| `/api/v1/therapy-sessions/:id`| MATCH | `therapy_session_usecase.go` / `TherapySessionController.php`| - |
| DELETE | `/therapy-sessions/:id`| `/api/v1/therapy-sessions/:id`| MATCH | `therapy_session_usecase.go` / `TherapySessionController.php`| - |
| GET | `/medical-records` | `/api/v1/medical-records` | MATCH | `medical_record_usecase.go` / `MedicalRecordController.php`| - |
| GET | `/medical-records/:id` | `/api/v1/medical-records/:id`| MATCH | `medical_record_usecase.go` / `MedicalRecordController.php`| - |
| POST | `/medical-records` | `/api/v1/medical-records` | MATCH | `medical_record_usecase.go` / `MedicalRecordController.php`| - |
| PUT | `/medical-records/:id` | `/api/v1/medical-records/:id`| MATCH | `medical_record_usecase.go` / `MedicalRecordController.php`| - |
| GET | `/payments` | `/api/v1/payments` | MATCH | `payment_handler.go` / `PaymentController.php` | - |
| GET | `/activity-logs` | `/api/v1/activity-logs` | MATCH | `activity_log_handler.go` / `ActivityLogController.php`| - |

---

## Detail Isu Endpoint

*(Tidak ada endpoint yang berstatus PARTIAL, MISSING, DIFFERENT, REGRESSION, ataupun UNKNOWN pada fase audit terbaru ini. Seluruh logika transaksi, validasi, dan pembatasan peran telah disinkronisasi sepenuhnya).*

---

## Final Summary

```text
Total Go Endpoints: 37
MATCH: 37
PARTIAL: 0
MISSING: 0
DIFFERENT: 0
REGRESSION: 0
UNKNOWN: 0

Critical Issues: 0
High Issues: 0
Medium Issues: 0
Low Issues: 0
```

**Migration Readiness**: **READY**
