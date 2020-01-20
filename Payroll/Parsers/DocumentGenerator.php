<?php

namespace Payroll\Parsers;

use App\Http\Requests\ReportRequest;
use BadMethodCallException;
use Carbon\Carbon;
use Cartalyst\Support\Collection;
use DOMPDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Payroll\Models\CompanyProfile;
use Payroll\Models\ReportTemplates;
use Schema;

/**
 * Class DocumentGenerator
 *
 *
 * Example:
 *
 *  public function generate($id, DocumentGenerator $generator)
 *  {
 *
 *      return $generator
 *          ->withModuleId(Deduction::MODULE_ID)
 *          ->setColumns($this->getColumns())
 *          ->withFormAction(route('deductions.document'))
 *          ->withItemId($id)
 *          ->view();
 *  }
 *
 * @category PHP
 * @package  Payroll\Parsers
 * @author   David Mjomba <smodavprivate@gmail.com>
 */
class DocumentGenerator
{
    /**
     *  Identifier for the PDF file type
     */
    const PDF = 'pdf';

    /**
     * Identifier for the XLS file type
     */
    const XLS = 'xls';

    /**
     * Identifier for the XLSX file type
     */
    const XLSX = 'xlsx';

    /**
     * Identifier for the CSV file type
     */
    const CSV = 'csv';

    /**
     * @var string
     */
    public $orientation;
    /**
     * @var array
     */
    public $fieldOrder;
    /**
     * @var array
     */
    public $rows;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $documentType = DocumentGenerator::XLS;
    public $allowNumeric = true;
    /**
     * @var Model
     */
    private $model;
    /**
     * @var string
     */
    private $formAction;
    /**
     * @var string
     */
    private $view = 'smodav.pdf.create';
    /**
     * @var int
     */
    private $itemId;
    /**
     * @var Collection
     */
    private $companyProfile;
    /**
     * @var array
     */
    private $columns;
    /**
     * @var ReportTemplates
     */
    private $templates;
    /**
     * @var Collection
     */
    private $presets;
    /**
     * @var int
     */
    private $moduleId;

    /**
     * DocumentGenerator constructor.
     *
     * @param ReportTemplates $templates
     */
    public function __construct(ReportTemplates $templates)
    {
        $this->companyProfile = CompanyProfile::first();
        $this->templates = $templates;
    }

    /**
     * @param mixed $moduleId
     *
     * @return $this
     */
    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->setPresets($moduleId);

        return $this;
    }

    /**
     * @param int $moduleId
     *
     * @return $this
     */
    public function setPresets($moduleId)
    {
        $this->presets = $this->templates
            ->whereModuleId($moduleId)
            ->get();

        return $this;
    }

    /**
     * @param mixed $orientation
     *
     * @return $this
     */
    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;

        return $this;
    }

    /**
     * @param mixed $rows
     *
     * @return $this
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param mixed $itemId
     *
     * @return $this
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = new $model();

        return $this;
    }

    /**
     * @param mixed $formAction
     *
     * @return $this
     */
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;

        return $this;
    }

    /**
     * @param mixed $view
     *
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param mixed $columns
     *
     * @return $this
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $fieldOrder
     *
     * @return $this
     */
    public function setFieldOrder($fieldOrder)
    {
        $this->fieldOrder = $fieldOrder;

        return $this;
    }

    /**
     * @param string $documentType
     *
     * @return $this
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Dynamically bind parameters to the pdf.
     *
     * @param $method
     * @param $parameters
     *
     * @return DocumentGenerator
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'with')) {
            return $this->with(substr($method, 4), $parameters[0]);
        }

        throw new BadMethodCallException("Method [$method] does not exist on view.");
    }

    /**
     * Add a piece of data to the view.
     *
     * @param  string|array $key
     * @param  mixed        $value
     *
     * @return $this
     */
    public function with($key, $value = null)
    {
        $set = 'set' . $key;
        $this->$set($value);

        return $this;
    }

    /**
     * Generate the report creation form
     *
     * @return View
     */
    public function view()
    {
        if (is_null($this->columns)) {
            $this->getColumns();
        }

        return view($this->view)
            ->withPresets($this->presets)
            ->with('formAction', $this->formAction)
            ->with('columns', $this->columns->unique())
            ->with('itemId', $this->itemId)
            ->with('moduleId', $this->moduleId);
    }

    /**
     * Get the available fields from the database table
     *
     * @return $this
     */
    public function getColumns()
    {
        $columns = collect();
        foreach (Schema::getColumnListing($this->model->getTable()) as $column) {
            if ($column == 'created_at' ||
                $column == 'updated_at' ||
                $column == 'deleted_at' ||
                $column == 'id'
            ) {
                continue;
            }

            $columns->push($column);
        }
        $this->columns = $columns->sort();

        return $this;
    }

    /**
     * Prepare the output document for rendering
     *
     * @param ReportRequest $request
     *
     * @return $this
     */
    public function prepare(Request $request)
    {
        $title = $request->get('title');
        if ($request->has('includeDate')) {
            $title .= ' as of ' . Carbon::now()->format('d F Y');
        }
        $this->setTitle($title);
        $this->setFieldOrder(explode(',', $request->get('order')));
        $this->setDocumentType($request->get('documentType'));

        return $this;
    }

    /**
     * Render the document in the specified format
     *
     * @param null $documentType
     *
     * @return mixed
     */
    public function render($documentType = null)
    {
        if ($documentType) {
            $this->documentType = $documentType;
        }
        $data = $this->getExcelData();
        $scope = $this->getExcelScope();
        $document = $this->generateDocument($data, $scope);

        return $document->export($this->documentType);
    }

    /**
     * Map the column headers to the rows
     *
     * @return array
     */
    private function getExcelData()
    {
        $record = array();
        $data = array();
        $rowNumber = 0;
        foreach ($this->rows as $row) {
            $rowNumber++;
            $record['No.'] = $rowNumber;

            foreach ($this->fieldOrder as $field) {
                $field = snake_case(str_replace('/', '_', $field));
                if (count(explode('-', $row->$field)) > 2) {
                    $record [title_case(str_replace('_', ' ', $field))] = Carbon::parse($row->$field)->format('d F Y');
                    continue;
                }
                $record [title_case(str_replace('_', ' ', $field))] = $row->$field;
            }
            $data [] = $record;
        }

        return $data;
    }

    /**
     * Get the cell range for the border creation
     *
     * @return string
     */
    private function getExcelScope()
    {
        $columnName = 'A';
        for ($i = 2; $i <= (count($this->fieldOrder) + 1); $i++) {
            $columnName++;
        }
        $scope = 'A1:' . $columnName . (count($this->rows) + 1);

        return $scope;
    }

    /**
     * Generate the document before rendering it to a given format
     *
     * @param $data
     * @param $scope
     *
     * @return mixed
     */
    private function generateDocument($data, $scope)
    {
        if ($this->documentType == DocumentGenerator::PDF) {
            return $this->generatePDF();
        }
        $title = $this->title;

        $document = Excel::create($title, function ($excel) use ($title, $data, $scope) {
            $excel->setTitle($title)->setCreator('CloudWage');
            $excel->sheet('Sheet 1', function ($sheet) use ($data, $scope) {
                $sheet->setStyle([
                    'font' => [
                        'name' => 'Times New Roman',
                        'size' => 12,
                    ]
                ]);
                $sheet->setPageMargin([1, 0.75, 1, 0.75]);
                $sheet->fromArray($data);
                $sheet->setFitToWidth(true);
                $sheet->setBorder($scope, 'thin');
                $sheet->setAutoSize(true);
                $sheet->setOrientation($this->orientation);
                $sheet->row(1, function ($row) {
                    $row->setFontWeight('bold');
                    $row->setAlignment('center');
                });
                $sheet->setWidth('A', 5);
            });

            $excel->getActiveSheet()->getHeaderFooter()
            ->setOddHeader('&B&U&C' . $this->companyProfile->name . ' - ' . $title)
            ->setEvenHeader('&B&U&C' . $this->companyProfile->name . ' - ' . $title);

            $excel->getActiveSheet()->getHeaderFooter()
                ->setOddFooter('&RPage &P of &N')
                ->setEvenFooter('&RPage &P of &N');
        });

        return $document;
    }

    /**
     * Generate the PDF format of the document
     *
     * @return Response
     */
    private function generatePDF()
    {
        $view = view('smodav.pdf.show')
            ->withAllowNumeric($this->allowNumeric)
            ->withRows($this->rows)
            ->withTitle($this->title)
            ->with('fieldOrder', $this->fieldOrder)
            ->withCompany($this->companyProfile);

        $pdf = new DOMPDF();
        
        $pdf->load_html($view->render());
        $pdf->set_paper('A4', $this->orientation);
        $pdf->render();

        header('Content-Type: application/pdf');

        return $pdf->stream($this->title, ['Attachment' => 0]);
    }
}
