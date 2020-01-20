<style>
    body {
        font-size: 0.7em;
        font-family: "helvetica", "Sans-Serif";
    }

    .s-table {
        width: 100%;
    }
    .s-table-header
    {
        background-color: #E5E5E5;
    }

    .s-table-header th {
        padding: 5px;
    }

    .s-table-summary
    {
        background-color: #E5E5E5;
    }

    .s-container table {
        width: 100%;
    }

    .s-container td {
        border: none !important;
    }

    .page-break
    {
        page-break-after: always;
    }

    .centerHead {
        display: block;
        margin: 0 auto;
        max-height: 60px;
    }
    .s-text-center {
        text-align: center;
    }

    .s-name {
        position: absolute;
    }

    .s-text-right {
        text-align: right;
    }

    table.data , .data th, .data td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 0 5px;
    }

    .page-heading h3 {
        line-height: 5px;
    }

    .totals {
        font-weight: 600;
    }
</style>
<img src="{{ public_path($company->logo) }}" alt="logo" class="centerHead">
<div class="body">
    <div class="s-text-center page-heading">
        <h3>{{ $company->name }}</h3>
        <h3>{{ $department->name }} Employees as of {{ \Carbon\Carbon::now()->format('d F Y') }}</h3>
        <h4>Total: {{ $assignments->count() }} Employees</h4>
    </div>
    <div class="pageBody">
        <table class="s-table data">
            <thead>
            <tr class="s-table-header">
                <th>#</th>
            @foreach($order as $ord)
                <th>
                    {{ title_case(str_replace('_', ' ', $ord)) }}
                </th>
            @endforeach

            </tr>
            </thead>
            <tbody>
            <?php $x = 0; ?>
            @foreach($assignments as $assignment)
                <?php $x ++; ?>
                <tr>
                    <td class="s-text-right">{{ $x }}</td>
                    @foreach($order as $ord)
                        @if(count(explode('-', $assignment->employee->$ord)) > 1)
                        <th>
                            {{ \Carbon\Carbon::parse($assignment->employee->$ord)->format('d F Y') }}
                        </th>
                        @else
                        <th>
                            {{ $assignment->employee->$ord }}
                        </th>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
<span class="page-break"></span>