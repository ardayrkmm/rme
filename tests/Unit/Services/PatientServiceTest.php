<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\PatientService;
use App\Interfaces\PatientRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Carbon\Carbon;

class PatientServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $patientService;
    protected $patientRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->patientRepositoryMock = Mockery::mock(PatientRepositoryInterface::class);
        $this->patientService = new PatientService($this->patientRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_patient_generates_medical_record_number()
    {
        // Mocking the today date
        Carbon::setTestNow('2026-07-04 10:00:00');
        
        // Arrange
        $data = [
            'name' => 'John Doe',
            'nik' => '1234567890123456',
        ];

        // Mock getting latest patient (returns null meaning no patient today yet)
        $this->patientRepositoryMock->shouldReceive('getLatestPatientToday')
                                    ->once()
                                    ->andReturn(null);

        // Expect creation with generated MRN
        $expectedData = array_merge($data, [
            'medical_record_number' => 'RM-20260704-0001'
        ]);

        $this->patientRepositoryMock->shouldReceive('create')
                                    ->once()
                                    ->with($expectedData)
                                    ->andReturn((object) $expectedData);

        // Act
        $result = $this->patientService->createPatient($data);

        // Assert
        $this->assertEquals('RM-20260704-0001', $result->medical_record_number);
    }

    public function test_create_patient_increments_medical_record_number()
    {
        // Mocking the today date
        Carbon::setTestNow('2026-07-04 10:00:00');
        
        // Arrange
        $data = [
            'name' => 'Jane Doe',
            'nik' => '9876543210987654',
        ];

        // Mock getting latest patient (returns a patient with existing sequence)
        $this->patientRepositoryMock->shouldReceive('getLatestPatientToday')
                                    ->once()
                                    ->andReturn((object) ['medical_record_number' => 'RM-20260704-0015']);

        // Expect creation with incremented MRN
        $expectedData = array_merge($data, [
            'medical_record_number' => 'RM-20260704-0016'
        ]);

        $this->patientRepositoryMock->shouldReceive('create')
                                    ->once()
                                    ->with($expectedData)
                                    ->andReturn((object) $expectedData);

        // Act
        $result = $this->patientService->createPatient($data);

        // Assert
        $this->assertEquals('RM-20260704-0016', $result->medical_record_number);
    }

    public function test_update_patient_removes_medical_record_number_from_data()
    {
        $id = 1;
        $data = [
            'name' => 'Updated Name',
            'medical_record_number' => 'HACKED-MRN', // Malicious attempt
        ];

        // Ensure repository update is called WITHOUT medical_record_number
        $expectedData = [
            'name' => 'Updated Name',
        ];

        $this->patientRepositoryMock->shouldReceive('update')
                                    ->once()
                                    ->with($id, $expectedData)
                                    ->andReturn(true);

        $result = $this->patientService->updatePatient($id, $data);

        $this->assertTrue($result);
    }
}
