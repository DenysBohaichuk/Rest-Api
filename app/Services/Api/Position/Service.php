<?php
namespace App\Services\Api\Position;

use App\Models\Base\Position;
use App\Models\Base\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Service
{


    public function getAllPositions()
    {
        $positions = Position::select('id', 'name')->get();

        if ($positions->isEmpty()) {
            throw new NotFoundHttpException('Positions not found');
        }

        return $positions;
    }



}
