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
            'expired'
        );

        $validator = Validator::make($allowed, [
            'nama' => 'required|string',
            'jenis_produk' => 'required', // nanti hanya di ijinkan 1 / 2 saja
            'deskripsi' => 'required|string',
            'kategori' => 'required',
            'berat' => 'required',
            'produk' => 'required',
            'foto.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        $hextime = dechex($timestamp);
        $hexid = dechex($user->id);
        // $kat = substr($allowed['kategori'], 0, 2);
        $kat = $allowed['kategori'];
        $jenis = $allowed['jenis_produk'] == 1 ? 'F' : 'K';
        $produkID = $jenis . $kat . $hexid . $hextime;

        $allowed['berat'] = explode(' ', $allowed['berat']);
        $allowed['produk_id'] = Str::upper($produkID);
        $allowed['user'] = $user;

        if ($user->as_store === 0) {
            $countInputProduk = count($allowed['produk']);

            if ($countInputProduk > 10) {
                return new Respons(false, 'User Maks hanya 10 varian produk');
            }

            $countProdDB = $this->produkRepo->totalProdukVarian($user->idMarket->user_id_market);

            if (($countProdDB + $countInputProduk) > 10) {
                return new Respons(false, 'User Maks hanya total 10 varian produk');
            }
        }

        if ($jenis == 'F') {

            $validator2 = Validator::make($allowed, [
                'gender' => 'required',
                'kondisi' => 'required',
            ]);
            if ($validator2->fails()) return new Respons(false, 'Validation Failed', $validator2->errors());

            $res = $this->produkRepo->createProdukFashion($allowed);

        } elseif ($jenis == 'K') {

            $validator2 = Validator::make($allowed, [
                'expired' => 'required|date',
            ]);
            if ($validator2->fails()) return new Respons(false, 'Validation Failed', $validator2->errors());

            $allowed['expired'] = date('Y-m-d',strtotime($allowed['expired']));
            $res = $this->produkRepo->createProdukHarian($allowed);

        }
        if(!$res[0]) return new Respons(false,$res[1]);

        return new Respons(true,'Berhasil memposting produk');
    }
}
