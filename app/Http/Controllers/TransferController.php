<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransfersResource;

class TransferController extends Controller
{
    public function transfer(TransferRequest $request): TransfersResource
    {
        return TransfersResource::make($request);
    }
}
