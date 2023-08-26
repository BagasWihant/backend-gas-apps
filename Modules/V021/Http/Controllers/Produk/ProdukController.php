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
            'img',
            'jenis_produk',
            'deskripsi',
            'gender',
            'kategori',
            'kondisi',
            'berat',
            'varian',
            'produk',
            'expired'
        );

        $validator = Validator::make($req->all(), [
            'nama' => 'required|string',
            'jenis_produk' => 'required', // nanti hanya di ijinkan 1 / 2 saja
            'deskripsi' => 'required|string',
            'kategori' => 'required',
            'berat' => 'required',
            'produk' => 'required',
            'img.*' => 'image|mimes:jpeg,png,jpg|max:2048|required',
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
        $allowed['user_id'] = $user->idMarket->user_id_market;


        $validatorRules = [
            'F' => ['gender' => 'required', 'kondisi' => 'required'],
            'K' => ['expired' => 'required|date']
        ];

        $validator = Validator::make($allowed, $validatorRules[$jenis]);
        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }

        if ($jenis == 'K') {
            $allowed['expired'] = date('Y-m-d', strtotime($allowed['expired']));
        }

        // USER BIASA TIDAK MEMILIKI TOKO
        if ($user->as_store == 0) {
            $countInputProduk = count($allowed['produk']);

            if ($countInputProduk > 10) {
                return new Respons(false, 'User maksimal hanya 10 varian per produk');
            }

            $countProdDB = $this->produkRepo->totalProdukVarian($user->idMarket->user_id_market);

            if (($countProdDB + $countInputProduk) > 10) {
                return new Respons(false, 'User maksimal hanya bisa membuat total 10 produk ');
            }

            $res = $this->produkRepo->createProdukNotStore($allowed);
        } else {
            switch ($jenis) {
                case 'F':
                    $res = $this->produkRepo->createProdukFashion($allowed);
                    break;
                case 'K':
                    $res = $this->produkRepo->createProdukHarian($allowed);
                    break;
                default:
                    $res = [false, 'Jenis produk tidak dikenali'];
                    break;
            }
        }

        return new Respons($res[0], $res[1]);
    }

    public function migrateProdukToStore(Request $req)
    {
        $user = $req->user();

        $res = $this->produkRepo->migrateProdukToStore($user);

        return new Respons($res[0], $res[1]);
    }
}
