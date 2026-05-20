<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyControlErrorRequest;
use App\Http\Requests\StoreControlErrorRequest;
use App\Models\ControlError;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ControlErrorController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('control_error_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlErrors = ControlError::get();



        return view('frontend.controlErrors.index', compact('controlErrors'));
    }

    public function create()
    {
        abort_if(Gate::denies('control_error_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.controlErrors.create');
    }

    public function store(StoreControlErrorRequest $request)
    {
        $controlError = ControlError::create($request->all());

        return redirect()->route('frontend.control-errors.index');
    }

    public function show(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');



        return view('frontend.controlErrors.show', compact('controlError'));
    }

    public function destroy(ControlError $controlError)
    {
        abort_if(Gate::denies('control_error_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $controlError->delete();

        return back();
    }

    public function massDestroy(MassDestroyControlErrorRequest $request)
    {
        $controlErrors = ControlError::find(request('ids'));

        foreach ($controlErrors as $controlError) {
            $controlError->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
