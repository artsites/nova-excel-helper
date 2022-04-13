<?php

namespace Artsites\NovaExcelHelper\Http\Controllers;

use App\Http\Controllers\Controller;
use Artsites\NovaExcelHelper\Classes\ExcelExport;
use Artsites\NovaExcelHelper\Classes\ExcelImport;
use Artsites\NovaExcelHelper\Http\Requests\ExcelRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExcelController extends Controller
{

    public function import(ExcelRequest $request){
        $file = $request->file;
        $model = $request->model;

        $rows = (new ExcelImport)->toCollection($file);

        foreach ($rows->first() as $row){
            $row['created_at'] = Carbon::now();
            $row['updated_at'] = Carbon::now();
        }

        $object = (new $model())->insert($rows->first()->toArray());

        return response()->json($object);
    }

    public function export(Request $request){
        return Excel::download(new ExcelExport($request->model), $request->name.'.xlsx' ?? 'export.xlsx');
    }

    public function getModels(){
        return config('nova-excel-helper.classes', []);
    }
}
