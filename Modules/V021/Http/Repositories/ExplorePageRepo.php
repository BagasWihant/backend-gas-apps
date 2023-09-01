<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use App\Models\Produk\ProdukMaster;
use App\Models\Produk\UserBasic\ProdukUserMain;
use Illuminate\Support\Facades\DB;


class ExplorePageRepo
{
    public function temukanProduk($data)
    {
        $binding = $data['binding'];
        $filter = $data['filter'];
        $where = 'MATCH(name) AGAINST(? IN BOOLEAN MODE)';


        if (!empty($filter)) {
            $binding[] = $filter;
            $where .= " AND MATCH(key_filter) AGAINST(? IN BOOLEAN MODE)";
        }

        $commonColumns = ['produk_id', 'name', 'img', 'rating', 'harga'];
        try {


            $query = DB::connection('mysql_market')
                ->table('produk_masters')
                ->select($commonColumns)
                ->whereRaw($where, $binding);

            if (isset($key['sort'])) {
                $sort = $key['sort'];
                if ($sort == 'hgdesc') $query->orderBy('harga', 'desc');
                elseif ($sort == 'hgasc') $query->orderBy('harga', 'asc');
                elseif ($sort == 'dtasc') $query->orderBy('created_at', 'asc');
                elseif ($sort == 'lrs') $query->orderBy('terjual', 'desc');
            }

            $results = $query->paginate(25);
            $res['data'] = $results->items();
            $res['maxPage'] = $results->lastPage();
            $res['currentPage'] = $results->currentPage();
            return $res;
        } catch (\Throwable $th) {
            return [false, 'Ada kesalahan server'];
        }
    }

    // Detail Produk
    public function detail($id)
    {
        $tableID = ProdukMaster::where('produk_id', $id)->select(['is_user', 'table'])->first();

        if ($tableID) {
            if ($tableID->is_user) $tableIDValue = $tableID->is_user;
            else $tableIDValue = $tableID->table;

            switch ($tableIDValue) {
                case 99:
                    $produk = ProdukUserMain::where('produk_id', $id)->select()->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];
                    break;

                case 1:
                    $produk = ProdukFashionMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];

                    break;

                case 6:
                    $produk = ProdukKebutuhanPokokMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];

                    break;

                case 7:
                    $produk = ProdukBuahSayurMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];

                    break;

                case 8:
                    $produk = ProdukMakanMinumMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];
                    break;

                case 9:
                    $produk = ProdukBumbuMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];
                    break;

                case 10:
                    $produk = ProdukMandiMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];
                    break;

                case 11:
                    $produk = ProdukKosmetikMain::where('produk_id', $id)->first();
                    $image = $produk->images()->select('img')->get();
                    $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok'])->get();
                    $data['image'] = $image;
                    $data['variasi'] = $variasi;
                    $data['produk'] = $produk;
                    $res = [true, $data];
                    break;

                default:
                    return [false, 'Kategori produk yang dipilih tidak ada'];
                    break;
            }
        } else {
            return [false, 'Produk Tidak Ditemukan'];
        }

        return [true,$res];
    }
}
