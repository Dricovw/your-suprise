<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DbOverviewController extends Controller
{
    public function index()
    {
        // Get all table names from SQLite
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        $overview = [];

        foreach ($tables as $table) {
            $name = $table->name;

            // Get columns
            $columns = DB::select("PRAGMA table_info(`$name`)");

            // Get row count
            $count = DB::table($name)->count();

            // Get preview rows (first 5)
            $rows = DB::table($name)->limit(5)->get();

            $overview[] = [
                'name'    => $name,
                'columns' => $columns,
                'count'   => $count,
                'rows'    => $rows,
            ];
        }

        return view('db-overview', compact('overview'));
    }
}