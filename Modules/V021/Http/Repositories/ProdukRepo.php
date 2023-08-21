<?php

namespace Modules\V021\Http\Repositories;

use App\Http\Resources\Respons;
use Illuminate\Support\Facades\File;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\Fashion\ProdukFashionImage;
use App\Models\Produk\Fashion\ProdukFashionVariasi;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokImage;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokVariasi;
use Illuminate\Support\Facades\DB;

class ProdukRepo
{

    public function createProdukFashion($data)
    {
        try {
            DB::beginTransaction();
            $user_id = $data['user']->idMarket->user_id_market;
            // BUAT MAIN PRODUK
            ProdukFashionMain::create([
                'produk_id' => $data['produk_id'],
                'user_id_market' => $user_id,
                'name' => $data['nama'],
                'desc' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'gender' => $data['gender'],
                'kondisi' => $data['kondisi'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ]);

            // BUAT PRODUK VARIAN
            foreach ($data['produk'] as $produk) {

                if ($data['user']->as_store == 0 && $produk['stok'] > 2) {
                    return [false, 'User maksimal 2 Stok per produk'];
                }

                $varian = explode(',', $produk['variant']);
                ProdukFashionVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $user_id,
                    'var_1' => $varian[0],
                    'var_2' => $varian[1],
                    'harga' => $produk['harga'],
                    'stok' => $produk['stok'],
                ]);
            }

            // SIMPAN IMG PRODUK
            $urut = 0;
            $path = public_path('image/produk/fashion');
            if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

            foreach ($data['foto'] as $foto) {
                $ext = $foto->getClientOriginalExtension();
                $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                $foto->move($path, $fileName);

                ProdukFashionImage::create([
                    'produk_id' => $data['produk_id'],
                    'img' => $fileName
                ]);
                $urut++;
            }
            DB::commit();
            return [true, 'Berhasil memposting produk fashion'];
        } catch (\Throwable $th) {
            DB::rollBack();
            // return [false, $th->getMessage()];
            return [false, 'Ada kesalahan memposting produk'];
            //throw $th;
        }
    }

    public function createProdukHarian($data)
    {
        try {
            DB::beginTransaction();

            $user_id = $data['user']->idMarket->user_id_market;
            ProdukKebutuhanPokokMain::create([
                'produk_id' => $data['produk_id'],
                'user_id_market' => $user_id,
                'name' => $data['nama'],
                'desc' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'expired' => $data['expired'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ]);

            // SIMPAN VARIAN PRODUK
            foreach ($data['produk'] as $produk) {
                $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variant']);
                ProdukKebutuhanPokokVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $user_id,
                    'var_1' => $varian[0],
                    'var_2' => $varian[1],
                    'harga' => $produk['harga'],
                    'stok' => $produk['stok'],
                ]);
            }

            // SIMPAN GAMBAR PRODUK
            $urut = 0;
            $path = public_path('image/produk/kebutuhan_pokok');
            if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

            foreach ($data['foto'] as $foto) {
                $ext = $foto->getClientOriginalExtension();
                $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                $foto->move($path, $fileName);

                ProdukKebutuhanPokokImage::create([
                    'produk_id' => $data['produk_id'],
                    'img' => $fileName
                ]);
                $urut++;
            }

            DB::commit();
            return [true, 'Berhasil posting produk kebutuhan pokok'];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [false, $th->getMessage()];
        }
    }

    public function totalProdukVarian($user_id)
    {
        $t = ProdukFashionVariasi::where('user_id_market',$user_id)->count();
        return $t;
    }
}
