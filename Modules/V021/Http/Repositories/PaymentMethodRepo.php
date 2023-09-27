<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Marketplace\PaymentMethod;

class PaymentMethodRepo{
    public function list($req){
        $arr = ['wallet','1'];
        $model = PaymentMethod::select(['id','name']);
        if(in_array($req['t'],$arr)) return $model->where('type',1)->get();

        $res = $model->get();
        return $res;
    }
}
