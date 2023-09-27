<?php

namespace Modules\V021\Http\Controllers\Orders;

use App\Enums\ShippingMethodEnum;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\V021\Http\Repositories\PaymentMethodRepo;

class PaymentMethodController extends Controller
{
    private $paymentMethodRepo ;
    public function __construct(PaymentMethodRepo $paymentMethodRepo)
    {
        $this->paymentMethodRepo = $paymentMethodRepo;
    }
    public function index(Request $req)
    {
        return $this->paymentMethodRepo->list($req->all());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
