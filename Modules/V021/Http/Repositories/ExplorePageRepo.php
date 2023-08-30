<?php

namespace Modules\V021\Http\Repositories;

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

            $results = $query->paginate(2);
            $res['data'] = $results->items();
            $res['maxPage'] = $results->lastPage();
            $res['currentPage'] = $results->currentPage();
            return $res;
        } catch (\Throwable $th) {
            return [false, 'Ada kesalahan server'];
        }
    }
}
