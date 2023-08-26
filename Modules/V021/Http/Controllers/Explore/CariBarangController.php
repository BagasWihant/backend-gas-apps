<?php

namespace Modules\V021\Http\Controllers\Explore;

use Faker\Factory;
use Nette\Utils\Random;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Modules\V021\Http\Repositories\ProdukRepo;

class CariBarangController extends Controller
{
    public function index($key, Request $req)
    {
        $begin = microtime(true);
        $commonColumns = ['produk_id', 'name', 'img', 'rating'];

        $fashionResults = DB::connection('mysql_market')->table('produk_user_mains')
            ->select($commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $bumbuResults = DB::connection('mysql_market')->table('produk_bumbu_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $buahResults = DB::connection('mysql_market')->table('produk_buah_sayur_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $pokokResults = DB::connection('mysql_market')->table('produk_kebutuhan_pokok_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $kosmetikResults = DB::connection('mysql_market')->table('produk_kosmetik_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $makanResults = DB::connection('mysql_market')->table('produk_makan_minum_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $mandiResults = DB::connection('mysql_market')->table('produk_mandi_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $userResults = DB::connection('mysql_market')->table('produk_user_mains')
            ->select(...$commonColumns)
            ->whereRaw("MATCH(name, deskripsi) AGAINST(? IN BOOLEAN MODE)", [$key . '*']);

        $results = $fashionResults
            ->union($bumbuResults)
            ->union($buahResults)
            ->union($pokokResults)
            ->union($kosmetikResults)
            ->union($makanResults)
            ->union($mandiResults)
            ->union($userResults)
            ->get();
        $end = microtime(true);
        // $results['time'] = $end - $begin;
        $rea = response()->json($results);
        return $rea;
    }

}
