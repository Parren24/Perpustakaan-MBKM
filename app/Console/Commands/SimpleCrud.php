<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SimpleCrud extends Command
{
    protected $signature = 'mwy:simplecrud {name} {schema?} {--sub= : Subfolder untuk penyimpanan file} {--prefix= : group prefix} {--output=mvcs : Komponen yang akan dibuat (model, view, controller, migration)} {--rollback : Menghapus file yang telah dibuat}';

    protected $description = 'Generate a simple CRUD for a given model';

    protected $tableName = '';
    protected $tableId = '';
    protected $tableSubject = '';
    protected $className = '';
    protected $classTitle = '';
    protected $subNamespace = '';
    protected $subFolder = '';
    protected $routePrefix = '';
    protected $fields = [];


    public function handle()
    {
        $name      = $this->argument('name');
        $schema    = json_decode($this->argument('schema'), true);
        $subfolder = $this->option('sub');
        $prefix    = $this->option('prefix');
        $output    = strtolower($this->option('output'));
        $rollback  = $this->option('rollback');

        if ($rollback) {
            $this->generateVariable($name, $subfolder, $prefix);
            $this->rollbackFiles($output);
            $this->info('Rolled back successfully!');
            return;
        } else {
            $this->readSchema($schema);
            $this->generateVariable($name, $subfolder, $prefix);

            if (!$this->fields) {
                $this->error('Format schema tidak valid.');
                return;
            }

            if (strpos($output, 's') !== false) {
                $this->generateMigration();
            }

            if (strpos($output, 'm') !== false) {
                $this->generateModel();
            }

            if (strpos($output, 'c') !== false) {
                $this->generateController();
            }

            if (strpos($output, 'v') !== false) {
                $this->generateView();
            }

            if (strpos($output, 'c') !== false || strpos($output, 'v') !== false) {
                $this->addRouting();
            }

            $this->info('Simple CRUD by MWY generated successfully!');
        }
    }

    protected function rollbackFiles($output)
    {
        if (strpos($output, 's') !== false) {
            $migrationFiles = glob(database_path('migrations/*_' . $this->tableName . '_table.php'));
            foreach ($migrationFiles as $file) {
                File::delete($file);
            }
        }

        if (strpos($output, 'm') !== false) {
            $modelFile = app_path('Models/') . $this->subFolder . $this->className . '.php';
            File::delete($modelFile);
        }

        if (strpos($output, 'c') !== false) {
            $controllerFile = app_path('Http/Controllers/') . $this->subFolder . $this->className . 'Controller.php';
            File::delete($controllerFile);
        }

        if (strpos($output, 'v') !== false) {
            $viewFile = resource_path('views/pages/') . strtolower($this->subFolder) . strtolower($this->tableName) . '.blade.php';
            File::delete($viewFile);
        }

        if (strpos($output, 'c') !== false || strpos($output, 'v') !== false) {
            $this->removeRouting();
        }
    }

    protected function removeRouting()
    {
        $routingFile = base_path('routes/web.php');
        $route       = "generalRoute(App\\Http\\Controllers" . $this->subNamespace . '\\' . $this->className . "Controller::class, '" . $this->snakeToKebab($this->tableName) . "'" . ($this->routePrefix ? ", '$this->routePrefix'" : '') . ");";

        $fileContents    = File::get($routingFile);
        $updatedContents = str_replace($route . PHP_EOL, '', $fileContents);
        File::put($routingFile, $updatedContents);
    }

    protected function generateMigration()
    {
        $migrationName   = 'create_' . $this->tableName . '_table';
        $tableName       = $this->tableName;
        $schemaBlueprint = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim($fieldInfo[0]);
            $fieldType     = trim($fieldInfo[1]);
            $fieldNullable = trim($fieldInfo[2]);
            $fieldInput    = trim($fieldInfo[3]);

            // Check if field type has length/size specified
            $fieldTypeWithLength = explode('(', $fieldType);
            $fieldType           = $fieldTypeWithLength[0];
            $fieldLength         = null;
            if (count($fieldTypeWithLength) > 1) {
                $fieldLength = rtrim($fieldTypeWithLength[1], ')');
            }

            // Map field type to Laravel migration method
            switch ($fieldType) {
                case 'id':
                    $migrationMethod = 'id';
                    break;
                case 'primary':
                    $migrationMethod = 'string';
                    break;
                case 'int':
                    $migrationMethod = 'integer';
                    break;
                case 'varchar':
                    $migrationMethod = 'string';
                    break;
                case 'text':
                    $migrationMethod = 'text';
                    break;
                case 'boolean':
                    $migrationMethod = 'boolean';
                    break;
                case 'datetime':
                    $migrationMethod = 'dateTime';
                    break;
                case 'date':
                    $migrationMethod = 'date';
                    break;
                default:
                    $migrationMethod = 'string';
            }

            // Add the field to the schema blueprint
            $schemaBlueprint .= "\$table->$migrationMethod('$fieldName'";
            if (!is_null($fieldLength)) {
                $schemaBlueprint .= ", $fieldLength";
            }
            if ($fieldType == 'primary')
                $schemaBlueprint .= ")->primary();\n            ";
            elseif ($fieldNullable)
                $schemaBlueprint .= ")->nullable();\n            ";
            else
                $schemaBlueprint .= ");\n            ";
        }

        $migrationFile = database_path('migrations/') . date('Y_m_d_His') . '_' . $migrationName . '.php';

        $template = str_replace(
            ['{{tableName}}', '{{schema}}'],
            [$tableName, $schemaBlueprint],
            File::get(base_path('stubs/migration.stub'))
        );

        file_put_contents($migrationFile, $template);
    }

    protected function generateModel()
    {
        $modelDirectory = app_path('Models/') . $this->subFolder;
        File::makeDirectory($modelDirectory, 0755, true, true);

        $modelFile = $modelDirectory . $this->className . '.php';

        $template = str_replace(
            [
                '{{currTime}}',
                '{{subNamespace}}',
                '{{modelName}}',
                '{{tableName}}',
                '{{tableId}}',
                '{{tableSubject}}',
                '{{fillable}}',
                '{{casts}}'
            ],
            [
                date('Y-m-d H:i:s'),
                $this->subNamespace,
                $this->className,
                $this->tableName,
                $this->tableId,
                $this->tableSubject,
                $this->generateFillable($this->fields),
                $this->generateCasts($this->fields)
            ],
            File::get(base_path('stubs/model.stub'))
        );

        file_put_contents($modelFile, $template);
    }

    protected function generateController()
    {
        $controllerDirectory = app_path('Http/Controllers/') . $this->subFolder;
        File::makeDirectory($controllerDirectory, 0755, true, true);

        $controllerFile = $controllerDirectory . $this->className . 'Controller.php';

        $template = str_replace(
            [
                '{{currTime}}',
                '{{subNamespace}}',
                '{{modelName}}',
                '{{className}}',
                '{{classTitle}}',
                '{{paramValue}}',
                '{{tableName}}',
                '{{tableId}}',
                '{{viewBlade}}',
                '{{columnView}}',
                '{{validationField}}',
                '{{dataStore}}',
                '{{dataView}}',
                '{{routePrefix}}'
            ],
            [
                date('Y-m-d H:i:s'),
                $this->subNamespace,
                $this->className,
                $this->className . 'Controller',
                $this->classTitle,
                str_replace('_', '-', $this->tableName),
                $this->snakeToKebab($this->tableName),
                $this->tableId,
                trim(($this->subNamespace ? str_replace('\\', '.', strtolower($this->subNamespace)) . '.' : '') . $this->tableName, '.'),
                $this->generateColumnView(),
                $this->generateValidationField(),
                $this->generateDataStore(),
                $this->generateDataView(),
                ($this->routePrefix ? "$this->routePrefix" : ""),
            ],
            File::get(base_path('stubs/controller.stub'))
        );

        file_put_contents($controllerFile, $template);
    }

    protected function generateView()
    {
        $viewDirectory = resource_path('views/pages/') . strtolower($this->subFolder);
        File::makeDirectory($viewDirectory, 0755, true, true);

        $viewFile = $viewDirectory . strtolower($this->tableName) . '.blade.php';

        $template = str_replace(
            [
                '{{tableName}}',
                '{{classTitle}}',
                '{{formInput}}',
                '{{routePrefix}}'
            ],
            [
                $this->snakeToKebab($this->tableName),
                $this->classTitle,
                $this->generateFormInput(),
                ($this->routePrefix ? "$this->routePrefix." : ""),
            ],
            File::get(base_path('stubs/view.stub'))
        );

        file_put_contents($viewFile, $template);
    }

    protected function addRouting()
    {
        $routingFile = base_path('routes/web.php');

        $route = "\ngeneralRoute(App\Http\Controllers" . $this->subNamespace . '\\' . $this->className . "Controller::class, '" . $this->snakeToKebab($this->tableName) . "'" . ($this->routePrefix ? ", '$this->routePrefix'" : '') . ");";

        File::append($routingFile, $route . PHP_EOL);
    }

    protected function generateFillable($schema)
    {
        $fillable = '';

        foreach ($schema as $field) {
            if ($field[1] != 'id')
                $fillable .= "'" . $field[0] . "', ";
        }

        return $fillable;
    }

    protected function generateCasts($schema)
    {
        $casts = '';

        foreach ($schema as $field) {
            if ($field[1] === 'int' || $field[1] === 'id') {
                $casts .= "'{$field[0]}' => 'string', ";
            } elseif ($field[1] === 'boolean') {
                $casts .= "'{$field[0]}' => 'boolean', ";
            }
        }

        return $casts;
    }

    protected function generateColumnView()
    {
        $dttableView = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim(isset($fieldInfo[0]) ? $fieldInfo[0] : '');
            $fieldType     = trim(isset($fieldInfo[1]) ? $fieldInfo[1] : '');
            $fieldNullable = trim(isset($fieldInfo[2]) ? $fieldInfo[2] : '');
            $htmlInput     = trim(isset($fieldInfo[3]) ? $fieldInfo[3] : '');
            $htmlTitle     = trim(isset($fieldInfo[4]) ? $fieldInfo[4] : '');
            $htmlRequired  = trim(isset($fieldInfo[5]) ? $fieldInfo[5] : '');
            $tableShow     = trim(isset($fieldInfo[6]) ? $fieldInfo[6] : '');

            if ($tableShow)
                $dttableView .= "Column::make(['width' => '', 'title' => '$htmlTitle', 'data' => '$fieldName', 'orderable' => true]),\n            ";

        }

        return $dttableView;
    }

    protected function generateValidationField()
    {
        $validation = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim(isset($fieldInfo[0]) ? $fieldInfo[0] : '');
            $fieldType     = trim(isset($fieldInfo[1]) ? $fieldInfo[1] : '');
            $fieldNullable = trim(isset($fieldInfo[2]) ? $fieldInfo[2] : '');
            $htmlInput     = trim(isset($fieldInfo[3]) ? $fieldInfo[3] : '');
            $htmlTitle     = trim(isset($fieldInfo[4]) ? $fieldInfo[4] : '');
            $htmlRequired  = trim(isset($fieldInfo[5]) ? $fieldInfo[5] : '');
            $tableShow     = trim(isset($fieldInfo[6]) ? $fieldInfo[6] : '');

            if ($htmlRequired)
                $validation .= "'$fieldName' => ['$htmlTitle', 'required'],\n                ";

        }

        return $validation;
    }

    protected function generateDataStore()
    {
        $data = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim(isset($fieldInfo[0]) ? $fieldInfo[0] : '');
            $fieldType     = trim(isset($fieldInfo[1]) ? $fieldInfo[1] : '');
            $fieldNullable = trim(isset($fieldInfo[2]) ? $fieldInfo[2] : '');
            $htmlInput     = trim(isset($fieldInfo[3]) ? $fieldInfo[3] : '');
            $htmlTitle     = trim(isset($fieldInfo[4]) ? $fieldInfo[4] : '');
            $htmlRequired  = trim(isset($fieldInfo[5]) ? $fieldInfo[5] : '');
            $tableShow     = trim(isset($fieldInfo[6]) ? $fieldInfo[6] : '');

            if ($fieldType != 'id')
                $data .= "\$data['$fieldName'] = clean_post('$fieldName');\n            ";

        }

        return $data;
    }

    protected function generateFormInput()
    {
        $form = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim(isset($fieldInfo[0]) ? $fieldInfo[0] : '');
            $fieldType     = trim(isset($fieldInfo[1]) ? $fieldInfo[1] : '');
            $fieldNullable = trim(isset($fieldInfo[2]) ? $fieldInfo[2] : '');
            $htmlInput     = trim(isset($fieldInfo[3]) ? $fieldInfo[3] : '');
            $htmlTitle     = trim(isset($fieldInfo[4]) ? $fieldInfo[4] : '');
            $htmlRequired  = trim(isset($fieldInfo[5]) ? $fieldInfo[5] : '');
            $tableShow     = trim(isset($fieldInfo[6]) ? $fieldInfo[6] : '');
            $isRequired    = $htmlRequired ? 'required' : '';

            if ($htmlTitle)
                if ($htmlInput == 'radio')
                    $form .= "<x-form.option class=\"mb-2\" type=\"radio\" label=\"$htmlTitle\" name=\"$fieldName\" value=\"\" $isRequired></x-form.option>\n            ";
                else if ($htmlInput == 'checkbox')
                    $form .= "<x-form.option class=\"mb-2\" type=\"checkbox\" label=\"$htmlTitle\" name=\"$fieldName\" value=\"\" $isRequired></x-form.option>\n            ";
                else if ($htmlInput == 'select')
                    $form .= "<x-form.select class=\"mb-2\" label=\"$htmlTitle\" name=\"$fieldName\" value=\"\" search=\"false\" $isRequired></x-form.select>\n            ";
                else if ($htmlInput == 'textarea' || $htmlInput == 'editor')
                    $form .= "<x-form.textarea class=\"mb-2\" " . ($htmlInput == 'editor' ? 'data-tinymce="simple"' : '') . " label=\"$htmlTitle\" name=\"$fieldName\" value=\"\" $isRequired></x-form.textarea>\n            ";
                else {
                    $type = 'text';
                    if ($fieldType == 'int' || $fieldType == 'float')
                        $type = 'number';
                    else if ($fieldType == 'date')
                        $type = 'date';
                    else if ($fieldType == 'datetime')
                        $type = 'datetime-local';
                    $form .= "<x-form.input class=\"mb-2\" type=\"$type\" label=\"$htmlTitle\" name=\"$fieldName\" value=\"\" $isRequired></x-form.input>\n            ";
                }
        }

        return $form;
    }

    protected function generateDataView()
    {
        $data = '';

        foreach ($this->fields as $field) {
            $fieldInfo = $field; // Parsing field info from schema

            // Extract field name and type
            $fieldName     = trim(isset($fieldInfo[0]) ? $fieldInfo[0] : '');
            $fieldType     = trim(isset($fieldInfo[1]) ? $fieldInfo[1] : '');
            $fieldNullable = trim(isset($fieldInfo[2]) ? $fieldInfo[2] : '');
            $htmlInput     = trim(isset($fieldInfo[3]) ? $fieldInfo[3] : '');
            $htmlTitle     = trim(isset($fieldInfo[4]) ? $fieldInfo[4] : '');
            $htmlRequired  = trim(isset($fieldInfo[5]) ? $fieldInfo[5] : '');
            $tableShow     = trim(isset($fieldInfo[6]) ? $fieldInfo[6] : '');

            if ($tableShow)
                $data .= "\$dt['$fieldName']     = \$value['$fieldName'];\n                ";
        }

        return $data;
    }

    function snakeToCamel($string)
    {
        // Ubah semua huruf menjadi huruf kecil
        $string = strtolower($string);
        // Ubah kata yang dipisahkan oleh underscore menjadi kapital
        $string = ucwords(str_replace('_', ' ', $string));
        // Hapus spasi di antara kata dan gabungkan
        $string = str_replace(' ', '', $string);
        return $string;
    }

    function snakeToKebab($string)
    {
        // Ubah semua huruf menjadi huruf kecil
        $string = strtolower($string);
        // Ubah kata yang dipisahkan oleh underscore menjadi kapital
        $string = str_replace('_', '-', $string);

        return $string;
    }

    function snakeToHuman($string)
    {
        // Ubah semua huruf menjadi huruf kecil
        $string = strtolower($string);
        // Ubah kata yang dipisahkan oleh underscore menjadi kapital
        $string = ucwords(str_replace('_', ' ', $string));
        // Hapus spasi di antara kata dan gabungkan
        return $string;
    }

    function readSchema($schema)
    {
        // Periksa apakah argumen schema kosong
        if (!$schema) {
            $this->info("Masukkan schema dalam format JSON. Ketik 'DONE' (tanpa tanda kutip) untuk menyelesaikan \n [\"column_db\", \"type_db\", is_nullable, \"html_type\", \"html_title\", is_required, is_data_show]:");

            $lines = [];
            while (true) {
                $line = trim(fgets(STDIN));
                if (strtoupper($line) === 'DONE') {
                    break;
                }
                $lines[] = $line;
            }

            $schema = implode("\n", $lines);
        }

        // Parse schema
        $schemaArray  = json_decode($schema, true);
        $this->fields = $schemaArray;
    }

    function generateVariable($name, $subfolder, $prefix)
    {
        $this->tableName = strtolower($name);

        $this->routePrefix = $this->snakeToKebab($prefix);

        $tableId = '';
        foreach ($this->fields as $key => $value) {
            $fieldType           = trim($value[1]);
            $fieldTypeWithLength = explode('(', $fieldType);
            $fieldType           = $fieldTypeWithLength[0];

            if ($fieldType == 'id')
                $tableId = $value[0];
            elseif ($fieldType == 'primary')
                $tableId = $value[0];
        }
        $this->tableId = $tableId ?? 'id';

        $tableSubject       = isset($this->fields[1][0]) ? $this->fields[1][0] : '';
        $this->tableSubject = $tableSubject;

        $this->className  = $this->snakeToCamel($name);
        $this->classTitle = $this->snakeToHuman($name);

        $subNamespace       = ($subfolder ? '\\' . str_replace('/', '\\', $subfolder) : '');
        $this->subNamespace = $subNamespace;

        $subFolder       = ($subfolder ? str_replace('/', DIRECTORY_SEPARATOR, $subfolder . '/') : '');
        $this->subFolder = $subFolder;
    }
}
