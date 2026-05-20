<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SpreadsheetReader;

class CrudController extends Controller
{

    public $config;
    protected $modelClass;


    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function setConfig(array $config)
    {
        $this->config['id'] = '';
        $this->config['url'] = '';
        $this->config['title_singular'] = '';

        $this->config['import_type'] = 'parse'; // direct


        $this->config['table_columns'] = $this->modelClass::tableColumns() ?? [];
        $this->config['form_fields'] = $this->modelClass::formFields() ?? [];
        $this->config['list_view'] = $this->config['list_view'] ?? 'admin.crud.list';
        $this->config['list_js_view'] = $this->config['list_js_view'] ?? 'admin.crud.list_js';
        $this->config['create_view'] = $this->config['create_view'] ?? 'admin.crud.create';
        $this->config['edit_view'] = $this->config['edit_view'] ?? 'admin.crud.edit';
        $this->config['form_fields_required'] = $this->config['form_fields_required'] ?? [];
        $this->config['show_field_id'] = false;


        $this->config = array_merge($this->config, $config);

        if (!empty($this->config['form_fields_required'])) {
            $this->setFormFieldsRequired($this->config['form_fields_required']);
        }
    }


    public function setFormFieldsRequired(array $fields_array)
    {
        foreach ($fields_array as $field) {
            $this->config['form_fields'][$field]['required'] = true;
        }
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies($this->config['id'] . '_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
           // return $this->list();
        }

        return view('admin.crud.index', ['config' => $this->config]);
    }

    public function list(Request $request){

        if ($request->ajax()) {
            $query = $this->modelClass::query()->select(sprintf('%s.*', (new $this->modelClass)->getTable()));
            $table = Datatables::of($query);

            $this->addDefaultColumns($table);

            $columns = $this->modelClass::tableColumns();

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });

            foreach ($columns as $k => $col) {
                $table->editColumn($k, function ($row) use ($k) {
                    return $row->$k ? $row->$k : '';
                });
            }

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
    }

    protected function addDefaultColumns($table)
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function ($row) {
            $viewGate   = $this->config['id'] . '_show';
            $editGate   = $this->config['id'] . '_edit';
            $deleteGate = $this->config['id'] . '_delete';

            return view('admin.crud.datatablesActions', compact('viewGate', 'editGate', 'deleteGate', 'row'));
        });
    }

    public function create()
    {
        abort_if(Gate::denies($this->config['id'] . '_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view($this->config['create_view'], ['config' => $this->config]);
    }

    public function store(Request $request)
    {

        $this->modelClass::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Se creó correctamente'
        ]);
    }

    public function edit($id)
    {
        abort_if(Gate::denies($this->config['id'] . '_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $model = $this->modelClass::findOrFail($id);

        return view($this->config['edit_view'], ['config' => $this->config, 'model' => $model]);
        //return view('admin.' . $this->config['id'] . '.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = $this->modelClass::findOrFail($id);

        $model->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Se actualizó correctamente'
        ]);
    }

    public function show($id)
    {
        abort_if(Gate::denies($this->config['id'] . '_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return 'pendiente';
    }

    public function destroy($id)
    {
        abort_if(Gate::denies($this->config['id'] . '_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = $this->modelClass::findOrFail($id);

        $model->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Se eliminó correctamente'
        ]);
    }


    public function updateInline(Request $request)
    {
        abort_if(Gate::denies($this->config['id'] . '_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->all();
        $res = self::updateInlineSave($this->modelClass, $data);

        return response()->json($res);
    }


    public function parseCsvImport(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path      = $file->path();
        $hasHeader = $request->input('header', false) ? true : false;

        $reader  = new SpreadsheetReader($path);
        $headers = $reader->current();
        $lines   = [];

        $i = 0;
        while ($reader->next() !== false && $i < 5) {
            $lines[] = $reader->current();
            $i++;
        }

        $filename = Str::random(10) . '.csv';
        $file->storeAs('csv_import', $filename);

        $model     = new $this->modelClass();
        $fillables = $model->getFillable();
        $config = $this->config;

        return view(
            'admin.crud.csvImport.parseInput',
            compact('headers', 'filename', 'fillables', 'hasHeader', 'lines', 'config')
        );
    }

    public function processCsvImport(Request $request)
    {
        try {
            if ($this->config['import_type'] == 'direct') {
                return $this->importDirect($request);
            }
            $filename = $request->input('filename', false);
            $path     = storage_path('app/csv_import/' . $filename);

            $hasHeader = $request->input('hasHeader', false);

            $fields = $request->input('fields', false);
            $fields = array_flip(array_filter($fields));

            $modelName = $this->config['title_singular'];
            $model     = $this->modelClass;

            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {
                    if (isset($row[$k])) {
                        $tmp[$header] = $row[$k];
                    }
                }

                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {
                $model::insert($insert_item);
            }

            $rows  = count($insert);
            $table = Str::plural($modelName);

            File::delete($path);

            session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $rows, 'table' => $table]));

            return redirect($this->config['url']);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function importDirect(Request $request)
    {

        $file = $request->file('csv_file');
        $request->validate([
            'csv_file' => 'mimes:csv,txt',
        ]);

        $path      = $file->path();
        $hasHeader =  $request->input('header', false);
       
        $reader = new SpreadsheetReader($path);

        $insert = [];

        foreach ($reader as $key => $row) {

            if ($hasHeader && $key == 0) {               
                continue ;
            }

            if (empty($row[0])) { // insertamos si hay dato en la primera columna
                continue;
            }

            foreach ($row as &$valor) {
                $valor = mb_convert_encoding($valor, 'UTF-8', 'auto');
            }

            $insert[] = $row;
        }
        
        
        
        $insert_count = $this->processDataImport($insert);

        $table = Str::plural($this->config['title_singular']);

        File::delete($path);

        $label_res = '';
        /*if (count($repetidos) > 0) {
            $label_res = ' , Repetidos: ' . implode(", ", $repetidos);
        }*/

        session()->flash('message', trans('global.app_imported_rows_to_table', ['rows' => $insert_count, 'table' => $table]) . $label_res);

        return redirect($this->config['url']);
    }

    public function processDataImport($data)
    {
        return 0;
    }
}
