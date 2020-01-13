<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\RatingNotFound;
use App\Services\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * @var Rating
     */
    private $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    public function delete(Request $request, int $articleId): JsonResponse
    {
        $customerId = (int) $request->get('tokenPayload')['customerId'];
        $adminId = (int) $request->get('tokenPayload')['adminId'];

        try {
            $this->rating->deleteVote($articleId, $customerId, $adminId);
            return new JsonResponse(null, 200);
        } catch (RatingNotFound $exception) {
            return new JsonResponse(null, 404);
        }
    }
}
