<?php

namespace App\Services\Admin;

use App\Models\HttpLogger as ObjModel;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class HttpLoggerService extends BaseService
{
    protected string $folder = 'admin/http_logger';
    protected string $route = 'http_loggers';

    public function __construct(ObjModel $objModel)
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        if ($request->ajax()) {
            $obj = $this->getLogData();
            return DataTables::of($obj)
            ->editColumn('timestamp', function ($row) {
                return Carbon::parse($row['timestamp'])->format('Y-m-d h:i A');
            })
                ->editColumn('method', function ($row) {
                    if ($row['method'] == 'GET') {
                        return '<span class="badge badge-success">' . $row['method'] . '</span>';
                    } elseif ($row['method'] == 'POST') {
                        return '<span class="badge badge-info">' . $row['method'] . '</span>';
                    } elseif ($row['method'] == 'PUT') {
                        return '<span class="badge badge-warning">' . $row['method'] . '</span>';
                    } elseif ($row['method'] == 'DELETE') {
                        return '<span class="badge badge-danger">' . $row['method'] . '</span>';
                    }
                    return '<span class="badge badge-secondary">' . $row['method'] . '</span>';
                })
                ->editColumn('status', function ($row) {
                    if ($row['status'] >= 200 && $row['status'] < 300) {
                        return '<span class="badge badge-success">' . $row['status'] . '</span>';
                    } elseif ($row['status'] >= 300 && $row['status'] < 400) {
                        return '<span class="badge badge-info">' . $row['status'] . '</span>';
                    } elseif ($row['status'] >= 400 && $row['status'] < 500) {
                        return '<span class="badge badge-warning">' . $row['status'] . '</span>';
                    } elseif ($row['status'] >= 500) {
                        return '<span class="badge badge-danger">' . $row['status'] . '</span>';
                    }
                    return '<span class="badge badge-secondary">' . $row['status'] . '</span>';
                })
                ->editColumn('url', function ($row) {
                    // get after domain name
                    $url = parse_url($row['url'], PHP_URL_PATH);
                    return '<a href="' . $row['url'] . '" target="_blank">' . $url  . '</a>';
                })
                ->editColumn('ip', function ($row) {
                    return '<a href="https://www.iplocation.net/ip-lookup" target="_blank">' . $row['ip'] . '</a>';
                })
                ->editColumn('user_agent', function ($row) {
                    return '<a href="https://www.whatismybrowser.com/" target="_blank">' . $row['user_agent'] . '</a>';
                })
                ->addIndexColumn()
                ->escapeColumns([])
                ->make(true);
        } else {
            return view($this->folder . '/index', [
                'bladeName' => trns($this->route),
                'route' => $this->route,
            ]);
        }
    }

    private function getLogData()
    {
        $filePath = storage_path('logs/http_visits.log');

        // Check if file exists
        if (!File::exists($filePath)) {
            return collect(); // Return an empty collection if file doesn't exist
        }

        // Read file line by line and convert to a collection
        return collect(File::lines($filePath))
            ->map(fn($line) => json_decode($line, true))
            ->filter(fn($entry) => is_array($entry));
    }

    public function show($request, ObjModel $objModel)
    {
        $filePath = storage_path('logs/http_visits.log');

        // Check if file exists
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        return redirect()->route('http_loggers.index');
    }

    public function deleteLog()
    {
        $filePath = storage_path('logs/http_visits.log');

        // Check if file exists
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        return redirect()->route('http_loggers.index');
    }
}
