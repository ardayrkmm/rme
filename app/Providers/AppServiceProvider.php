<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        $this->app->bind(
            \App\Interfaces\AuthServiceInterface::class,
            \App\Services\AuthService::class
        );

        $this->app->bind(
            \App\Interfaces\UserServiceInterface::class,
            \App\Services\UserService::class
        );

        $this->app->bind(
            \App\Interfaces\ClinicRepositoryInterface::class,
            \App\Repositories\ClinicRepository::class
        );

        $this->app->bind(
            \App\Interfaces\ClinicServiceInterface::class,
            \App\Services\ClinicService::class
        );

        $this->app->bind(
            \App\Interfaces\PhysiotherapistRepositoryInterface::class,
            \App\Repositories\PhysiotherapistRepository::class
        );

        $this->app->bind(
            \App\Interfaces\PhysiotherapistServiceInterface::class,
            \App\Services\PhysiotherapistService::class
        );

        $this->app->bind(
            \App\Interfaces\PatientRepositoryInterface::class,
            \App\Repositories\PatientRepository::class
        );

        $this->app->bind(
            \App\Interfaces\PatientServiceInterface::class,
            \App\Services\PatientService::class
        );

        $this->app->bind(
            \App\Interfaces\AppointmentRepositoryInterface::class,
            \App\Repositories\AppointmentRepository::class
        );

        $this->app->bind(
            \App\Interfaces\AppointmentServiceInterface::class,
            \App\Services\AppointmentService::class
        );

        $this->app->bind(
            \App\Interfaces\MedicalRecordRepositoryInterface::class,
            \App\Repositories\MedicalRecordRepository::class
        );

        $this->app->bind(
            \App\Interfaces\MedicalRecordServiceInterface::class,
            \App\Services\MedicalRecordService::class
        );

        $this->app->bind(
            \App\Interfaces\DashboardServiceInterface::class,
            \App\Services\DashboardService::class
        );

        $this->app->bind(
            \App\Interfaces\NotificationServiceInterface::class,
            \App\Services\NotificationService::class
        );

        $this->app->bind(
            \App\Interfaces\ActivityLogRepositoryInterface::class,
            \App\Repositories\ActivityLogRepository::class
        );

        $this->app->bind(
            \App\Interfaces\ActivityLogServiceInterface::class,
            \App\Services\ActivityLogService::class
        );

        $this->app->bind(
            \App\Interfaces\ServiceMasterRepositoryInterface::class,
            \App\Repositories\ServiceMasterRepository::class
        );

        $this->app->bind(
            \App\Interfaces\ServiceMasterServiceInterface::class,
            \App\Services\ServiceMasterService::class
        );

        $this->app->bind(
            \App\Interfaces\PaymentRepositoryInterface::class,
            \App\Repositories\PaymentRepository::class
        );

        $this->app->bind(
            \App\Interfaces\PaymentServiceInterface::class,
            \App\Services\PaymentService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
