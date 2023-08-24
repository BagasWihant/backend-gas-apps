<?php

namespace Modules\V021\Http\Repositories;

use App\Http\Resources\Respons;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use App\Models\Produk\Bumbu\ProdukBumbuImage;
use App\Models\Produk\Mandi\ProdukMandiImage;
use App\Models\Produk\Bumbu\ProdukBumbuVariasi;
use App\Models\Produk\UserBasic\ProdukUserMain;
use App\Models\Produk\UserBasic\ProdukUserImage;
use App\Models\Produk\UserBasic\ProdukUserVariasi;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\Fashion\ProdukFashionImage;
use App\Models\Produk\Fashion\ProdukFashionVariasi;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\Kosmetik\ProdukKosmetikImage;
use App\Models\Produk\Kosmetik\ProdukKosmetikVariasi;
use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurImage;
use App\Models\Produk\BuahSayur\ProdukBuahSayurVariasi;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumImage;
use App\Models\Produk\MakanMinum\ProdukMakanMinumVariasi;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokImage;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokVariasi;

class ProdukRepo
{

    public function createProdukFashion($data)
    {
        try {
            DB::connection('mysql_market')->beginTransaction();
            $user_id = $data['user']->idMarket->user_id_market;
            // BUAT MAIN PRODUK
            ProdukFashionMain::create([
                'produk_id' => $data['produk_id'],
                'user_id_market' => $user_id,
                'name' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'gender' => $data['gender'],
                'kondisi' => $data['kondisi'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ]);

            // BUAT PRODUK VARIAN
            foreach ($data['produk'] as $produk) {
                $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);

                ProdukFashionVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $user_id,
                    'var_1' => $varian[0],
                    'var_2' => isset($varian[1]) ? $varian[1] : '',
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

            DB::connection('mysql_market')->commit();

            return [true, 'Berhasil memposting produk fashion'];
        } catch (\Throwable $th) {
            DB::connection('mysql_market')->rollBack();

            return [false, 'Ada kesalahan memposting produk'];
            //throw $th;
        }
    }

    public function createProdukHarian($data)
    {
        try {
            DB::connection('mysql_market')->beginTransaction();

            $user_id = $data['user']->idMarket->user_id_market;

            $kategoriClasses = [
                1 => [
                    'main' => ProdukKebutuhanPokokMain::class,
                    'variasi' => ProdukKebutuhanPokokVariasi::class,
                    'image' => ProdukKebutuhanPokokImage::class,
                    'folder' => 'kebutuhan_pokok',
                ],
                2 => [
                    'main' => ProdukBuahSayurMain::class,
                    'variasi' => ProdukBuahSayurVariasi::class,
                    'image' => ProdukBuahSayurImage::class,
                    'folder' => 'buah_sayur',
                ],
                3 => [
                    'main' => ProdukMakanMinumMain::class,
                    'variasi' => ProdukMakanMinumVariasi::class,
                    'image' => ProdukMakanMinumImage::class,
                    'folder' => 'makan_minum',
                ],
                4 => [
                    'main' => ProdukBumbuMain::class,
                    'variasi' => ProdukBumbuVariasi::class,
                    'image' => ProdukBumbuImage::class,
                    'folder' => 'bumbu',
                ],
                5 => [
                    'main' => ProdukMandiMain::class,
                    'variasi' => ProdukMandiVariasi::class,
                    'image' => ProdukMandiImage::class,
                    'folder' => 'mandi',
                ],
                6 => [
                    'main' => ProdukKosmetikMain::class,
                    'variasi' => ProdukKosmetikVariasi::class,
                    'image' => ProdukKosmetikImage::class,
                    'folder' => 'kosmetik',
                ],
            ];

            $kategori = $data['kategori'];

            if (isset($kategoriClasses[$kategori])) {
                $mainClass = $kategoriClasses[$kategori]['main'];
                $variasiClass = $kategoriClasses[$kategori]['variasi'];
                $imageClass = $kategoriClasses[$kategori]['image'];
                $folder = $kategoriClasses[$kategori]['folder'];

                // Create instances using the classes
                $mainData = [
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $user_id,
                    'name' => $data['nama'],
                    'deskripsi' => $data['deskripsi'],
                    'kategori' => $data['kategori'],
                    'expired' => $data['expired'],
                    'variasi' => $data['varian'],
                    'berat' => $data['berat'][0] . '.' . $data['berat'][1],
                ];
                $mainClass::create($mainData);

                // VARIASI
                foreach ($data['produk'] as $produk) {
                    $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);
                    $variasiData = [
                        'produk_id' => $data['produk_id'],
                        'user_id_market' => $user_id,
                        'var_1' => $varian[0],
                        'var_2' => isset($varian[1]) ? $varian[1] : '',
                        'harga' => $produk['harga'],
                        'stok' => $produk['stok'],
                    ];
                    $variasiClass::create($variasiData);
                }

                // GAMBAR PRODUK
                $urut = 0;
                $path = public_path('image/produk/'.$folder);
                if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

                foreach ($data['foto'] as $foto) {
                    $ext = $foto->getClientOriginalExtension();
                    $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                    $foto->move($path, $fileName);

                    $imageData = [
                        'produk_id' => $data['produk_id'],
                        'img' => $fileName
                    ];
                    $imageClass::create($imageData);
                    $urut++;
                }
            } else {
                return new Respons(false, 'Kategori ini tidak ada');
            }

            DB::connection('mysql_market')->commit();
            return [true, 'Berhasil posting produk kebutuhan pokok'];
        } catch (\Throwable $th) {
            DB::connection('mysql_market')->rollBack();
            return [false, $th->getMessage()];
        }
    }

    public function createProdukNotStore($data)
    {

        try {
            DB::connection('mysql_market')->beginTransaction();

            $user_id = $data['user']->idMarket->user_id_market;

            $createData = [
                'produk_id' => $data['produk_id'],
                'jenis' => $data['jenis_produk'],
                'user_id_market' => $user_id,
                'name' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ];

            if ($data['jenis_produk'] == 1) {
                $createData['gender'] = $data['gender'];
                $createData['kondisi'] = $data['kondisi'];
            } elseif ($data['jenis_produk'] == 2) {
                $createData['expired'] = $data['expired'];
            }

            ProdukUserMain::create($createData);

            // SIMPAN VARIAN PRODUK
            foreach ($data['produk'] as $produk) {

                if ($data['user']->as_store == 0 && $produk['stok'] > 2) {
                    DB::connection('mysql_market')->rollBack();
                    return [false, 'User maksimal 2 Stok per produk'];
                }

                $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);

                ProdukUserVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $user_id,
                    'var_1' => $varian[0],
                    'var_2' => isset($varian[1]) ? $varian[1] : '',
                    'harga' => $produk['harga'],
                    'stok' => $produk['stok'],
                ]);
            }

            // SIMPAN GAMBAR PRODUK
            $urut = 0;
            $path = public_path('image/produk/bukan_toko');
            if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

            foreach ($data['foto'] as $foto) {
                $ext = $foto->getClientOriginalExtension();
                $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;

                $foto->move($path, $fileName);

                ProdukUserImage::create([
                    'produk_id' => $data['produk_id'],
                    'img' => $fileName
                ]);
                $urut++;
            }

            DB::connection('mysql_market')->commit();
            return [true, 'Berhasil memposting produk'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::connection('mysql_market')->rollBack();
            return [false, $th->getMessage()];
        }
    }

    public function migrateProdukToStore($user)
    { //BELUM SELESAI
        $user_id = $user->idMarket->user_id_market;
        $produk = ProdukUserMain::where('user_id_market', $user_id)->get();
    }

    public function totalProdukVarian($user_id)
    {
        $t = ProdukUserVariasi::where('user_id_market', $user_id)->count();
        return $t;
    }
}
