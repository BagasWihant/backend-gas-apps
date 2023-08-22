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

        if ($user->as_store == 0) {
            $res = $this->produkRepo->createProdukNotStore($allowed);
        } else {
            if ($jenis == 'F') {
                $res = $this->produkRepo->createProdukFashion($allowed);
            } elseif ($jenis == 'K') {
                switch ($allowed['kategori']) {
                    case '1':
                        // 1.kebutuhan pokok
                        $res = $this->produkRepo->createProdukHarian($allowed);
                        break;

                    case '2':
                        // 2.sayur & buah
                        $res = $this->produkRepo->createProdukSayurBuah($allowed);
                        break;

                    case '3':
                        // 3.makan & minuman
                        $res = $this->produkRepo->createProdukMakanMinum($allowed);
                        break;

                    case '4':
                        // 4.bumbu dapur
                        $res = $this->produkRepo->createProdukBumbu($allowed);
                        break;

                    case '5':
                        // 5.perlengkapan mandi
                        $res = $this->produkRepo->createProdukMandi($allowed);
                        break;

                    case '6':
                        // 6.kosmetik
                        $res = $this->produkRepo->createProdukKosmetik($allowed);
                        break;

                    default:
                        $res = [false,'Kategori tidak ditemukan'];
                        break;
                }
                // $res = $this->produkRepo->createProdukHarian($allowed);
            }


            switch ($jenis) {
                case 'F':
                    $res = $this->produkRepo->createProdukFashion($allowed);
                    break;
                case 'K':
                    $kategori = $allowed['kategori'];
                    $kategoriMethods = [
                        '1' => 'createProdukHarian',
                        '2' => 'createProdukSayurBuah',
                        '3' => 'createProdukMakanMinum',
                        '4' => 'createProdukBumbu',
                        '5' => 'createProdukMandi',
                        '6' => 'createProdukKosmetik'
                    ];
                    $res = isset($kategoriMethods[$kategori]) ?
                        $this->produkRepo->{$kategoriMethods[$kategori]}($allowed) :
                        [false, 'Kategori tidak ditemukan'];
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
