<?php

namespace Modules\V021\Http\Controllers\Produk;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\V021\Http\Repositories\ProdukRepo;

class ProdukController extends Controller
{
    private $produkRepo;
    public function __construct(ProdukRepo $produkRepo)
    {
        $this->produkRepo = $produkRepo;
    }

    public function createProduk(Request $req)
    {
        // $start = microtime(true);
        $user = $req->user();
        $timestamp = Carbon::now()->timestamp;

        $allowed = $req->only(
            'nama',
            'jenis_produk',
            'deskripsi',
            'gender',
            'kategori',
            'kondisi',
            'berat',
            'varian',
            'produk',
            'foto',
        );

        $validator = Validator::make($allowed, [
            'nama' => 'required|string',
            'jenis_produk' => 'required', // nanti hanya di ijinkan 1 / 2 saja
            'deskripsi' => 'required|string',
            'gender' => 'required',
            'kategori' => 'required',
            'kondisi' => 'required',
            'berat' => 'required',
            'produk' => 'required',
            'foto.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        $hextime = dechex($timestamp);
        $hexid = dechex($user->id);
        $kat = substr($allowed['kategori'],0,2);
        $jenis = $allowed['jenis_produk'] == 1 ? 'F' : 'K';
        $produkID = $jenis . $kat . $hexid . $hextime;

        $allowed['berat'] = explode(' ',$allowed['berat']);
        $allowed['produk_id'] = Str::upper($produkID);
        $allowed['user'] = $user;

        if ($jenis == 'F') {
            $this->produkRepo->createProdukFashion($allowed);
        } elseif ($jenis == 'K') {
            $this->produkRepo->createHarian($produkID);
        }

    }
}
