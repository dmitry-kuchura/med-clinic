<?php

namespace App\Http\Controllers\Api;

use App\Actions\TestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateRequest;
use App\Http\Requests\Patients\PatientUpdateRequest;
use Illuminate\Http\Response;

class TestsController extends Controller
{
    private TestAction $testAction;

    public function __construct(TestAction $testAction)
    {
        $this->testAction = $testAction;
    }

    public function list()
    {
        $result = $this->testAction->list();

        return $this->returnResponse(['result' => $result]);
    }

    public function all()
    {
        $result = $this->testAction->all();

        return $this->returnResponse(['result' => $result]);
    }

    public function info($id)
    {
//        $result = $this->testAction->info($id);

//        return $this->returnResponse(['result' => $result]);
    }

    public function create(PatientCreateRequest $request)
    {
//        $this->testAction->create($request->all());

//        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function update(PatientUpdateRequest $request)
    {
//        $this->testAction->update($request->all());

//        return $this->returnResponse(['updated' => true], Response::HTTP_OK);
    }
}
