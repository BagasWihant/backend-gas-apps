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
use App\Models\Produk\Mandi\ProdukMandiVariasi;
use App\Models\Produk\UserBasic\ProdukUserMain;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\UserBasic\ProdukUserImage;
use App\Models\Produk\Fashion\ProdukFashionImage;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\UserBasic\ProdukUserVariasi;
use App\Models\Produk\Fashion\ProdukFashionVariasi;
use App\Models\Produk\Kosmetik\ProdukKosmetikImage;
use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurImage;
use App\Models\Produk\Kosmetik\ProdukKosmetikVariasi;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurVariasi;
use App\Models\Produk\MakanMinum\ProdukMakanMinumImage;
use App\Models\Produk\MakanMinum\ProdukMakanMinumVariasi;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokImage;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokVariasi;
use App\Models\Produk\ProdukMaster;

class ProdukRepo
{
    public function createProdukFashion($data)
    {
        try {
            DB::connection('mysql_market')->beginTransaction();

            // BUAT PRODUK VARIAN
            $harga = null;
            foreach ($data['produk'] as $produk) {
                $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);

                if ($harga === null || $produk['harga'] < $harga) {
                    $harga = $produk['harga'];
                }

                ProdukFashionVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $data['user_id'],
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
            $preview = '';
            foreach ($data['img'] as $foto) {
                $ext = $foto->getClientOriginalExtension();
                $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                if ($urut == 0) $preview = $fileName;
                $foto->move($path, $fileName);

                ProdukFashionImage::create([
                    'produk_id' => $data['produk_id'],
                    'img' => $fileName
                ]);
                $urut++;
            }

            // BUAT MAIN PRODUK
            $mainData = [
                'produk_id' => $data['produk_id'],
                'user_id_market' => $data['user_id'],
                'name' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'gender' => $data['gender'],
                'kondisi' => $data['kondisi'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ];
            ProdukFashionMain::create($mainData);
            $insertMaster = [$mainData, $harga, $preview, 1];
            $this->InsertProdukMasterSearch($insertMaster);

            DB::connection('mysql_market')->commit();

            return [true, 'Berhasil memposting produk fashion'];
        } catch (\Throwable $th) {
            DB::connection('mysql_market')->rollBack();
            return [false, $th->getMessage()];
            // return [false, 'Ada kesalahan memposting produk'];
            //throw $th;
        }
    }

    public function createProdukHarian($data)
    {
        try {
            DB::connection('mysql_market')->beginTransaction();

            $kategoriClasses = [
                6 => [
                    'main' => ProdukKebutuhanPokokMain::class,
                    'variasi' => ProdukKebutuhanPokokVariasi::class,
                    'image' => ProdukKebutuhanPokokImage::class,
                    'folder' => 'kebutuhan_pokok',
                ],
                7 => [
                    'main' => ProdukBuahSayurMain::class,
                    'variasi' => ProdukBuahSayurVariasi::class,
                    'image' => ProdukBuahSayurImage::class,
                    'folder' => 'buah_sayur',
                ],
                8 => [
                    'main' => ProdukMakanMinumMain::class,
                    'variasi' => ProdukMakanMinumVariasi::class,
                    'image' => ProdukMakanMinumImage::class,
                    'folder' => 'makan_minum',
                ],
                9 => [
                    'main' => ProdukBumbuMain::class,
                    'variasi' => ProdukBumbuVariasi::class,
                    'image' => ProdukBumbuImage::class,
                    'folder' => 'bumbu',
                ],
                10 => [
                    'main' => ProdukMandiMain::class,
                    'variasi' => ProdukMandiVariasi::class,
                    'image' => ProdukMandiImage::class,
                    'folder' => 'mandi',
                ],
                11 => [
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

                // VARIASI
                $harga = null;
                foreach ($data['produk'] as $produk) {

                    if ($harga === null || $produk['harga'] < $harga) {
                        $harga = $produk['harga'];
                    }


                    $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);
                    $variasiData = [
                        'produk_id' => $data['produk_id'],
                        'user_id_market' => $data['user_id'],
                        'var_1' => $varian[0],
                        'var_2' => isset($varian[1]) ? $varian[1] : '',
                        'harga' => $produk['harga'],
                        'stok' => $produk['stok'],
                    ];
                    $variasiClass::create($variasiData);
                }

                // GAMBAR PRODUK
                $urut = 0;
                $path = public_path('image/produk/' . $folder);
                if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);
                $preview = '';
                foreach ($data['img'] as $foto) {
                    $ext = $foto->getClientOriginalExtension();
                    $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                    if ($urut == 0) $preview = $fileName;
                    $foto->move($path, $fileName);

                    $imageData = [
                        'produk_id' => $data['produk_id'],
                        'img' => $fileName
                    ];
                    $imageClass::create($imageData);
                    $urut++;
                }

                // Create instances using the classes
                $mainData = [
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $data['user_id'],
                    'name' => $data['nama'],
                    'deskripsi' => $data['deskripsi'],
                    'kategori' => $data['kategori'],
                    'expired' => $data['expired'],
                    'variasi' => $data['varian'],
                    'berat' => $data['berat'][0] . '.' . $data['berat'][1],
                ];
                $mainClass::create($mainData);
                $insertMaster = [$mainData, $harga, $preview, $data['kategori']];
                $this->InsertProdukMasterSearch($insertMaster);
            } else {
                return [false, 'Kategori ini tidak ada'];
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

            $createData = [
                'produk_id' => $data['produk_id'],
                'jenis' => $data['jenis_produk'],
                'user_id_market' => $data['user_id'],
                'name' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'kategori' => $data['kategori'],
                'variasi' => $data['varian'],
                'berat' => $data['berat'][0] . '.' . $data['berat'][1],
            ];

            if ($data['jenis_produk'] == 1) {
                $createData['gender'] = $data['gender'];
                $createData['kondisi'] = $data['kondisi'];
                // MAPPING KATEGORI
                $kt = [1, 2, 3, 4, 5];
                if (in_array($data['kategori'], $kt)) {
                    $kategori = 1;
                } else {
                    throw new \Exception("Kategori ini Tidak ada");
                }
            } elseif ($data['jenis_produk'] == 2) {
                $createData['expired'] = $data['expired'];
                $kt = [6, 7, 8, 9, 10, 11];
                if (in_array($data['kategori'], $kt)) {
                    $kategori = $data['kategori'];
                } else {
                    throw new \Exception("Kategori ini Tidak ada");
                }
            }


            // SIMPAN VARIAN PRODUK
            $harga = null;
            foreach ($data['produk'] as $produk) {

                if ($data['user']->as_store == 0 && $produk['stok'] > 2) {
                    DB::connection('mysql_market')->rollBack();
                    return [false, 'User maksimal 2 Stok per produk'];
                }

                if ($harga === null || $produk['harga'] < $harga) {
                    $harga = $produk['harga'];
                }

                $varian = ($data['varian'] === '-') ? ['', ''] : explode(',', $produk['variasi']);
                ProdukUserVariasi::create([
                    'produk_id' => $data['produk_id'],
                    'user_id_market' => $data['user_id'],
                    'var_1' => $varian[0],
                    'var_2' => isset($varian[1]) ? $varian[1] : '',
                    'harga' => $produk['harga'],
                    'stok' => $produk['stok'],
                ]);
            }

            // SIMPAN GAMBAR PRODUK
            $urut = 0;
            $preview = '';
            $path = public_path('image/produk/bukan_toko');
            if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

            foreach ($data['img'] as $foto) {
                $ext = $foto->getClientOriginalExtension();
                $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
                if ($urut == 0) $preview = $fileName;

                $foto->move($path, $fileName);

                ProdukUserImage::create([
                    'produk_id' => $data['produk_id'],
                    'img' => $fileName
                ]);
                $urut++;
            }

            ProdukUserMain::create($createData);


            $insertMaster = [$createData, $harga, $preview, $kategori];
            $this->InsertProdukMasterSearch($insertMaster,99);

            DB::connection('mysql_market')->commit();

            return [true, 'Berhasil memposting produk'];
        } catch (\Throwable $th) {
            //throw $th;
            DB::connection('mysql_market')->rollBack();
            return [false, $th->getMessage()];
        }
    }

    public function migrateProdukToStore($user_id)
    { //BELUM SELESAI
        $produk = ProdukUserMain::where('user_id_market', $user_id)->get();
    }

    public function totalProdukVarian($user_id)
    {
        $t = ProdukUserVariasi::where('user_id_market', $user_id)->count();
        return $t;
    }

    public function InsertProdukMasterSearch($data,$user = null)
    {
        // FORMAT FILTER [ KATEGORI KONDISI ] SEMENTARA HANYA ITU
        $filter = "";

        $kategori = $data[0]['kategori']; // kategori
        $filter .= "kat" . $kategori . " ";
        $data = [
            'produk_id' => $data[0]['produk_id'],
            'name'  => $data[0]['name'],
            'deskripsi' => $data[0]['deskripsi'],
            'diskon_harga' => 0,
            'key_filter' => $filter,
            'harga' => $data[1],
            'img' => $data[2],
            'table' => $data[3],
            'is_user' => $user
        ];
        ProdukMaster::create($data);
    }
}
