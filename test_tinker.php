$patient = \App\Models\Patient::latest()->first();
$request = \Illuminate\Http\Request::create('/api/v1/patients/'.$patient->id.'/medical-records', 'GET');
$request->setUserResolver(function () { return \App\Models\User::where('role', 'admin')->first(); });
$controller = app(\App\Http\Controllers\Api\V1\MedicalRecordController::class);
$response = $controller->history($request, $patient->id);
echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
