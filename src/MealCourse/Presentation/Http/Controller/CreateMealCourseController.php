<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseDto;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateMealCourseCommandFactory $cmdFactory,
        private readonly MealCourseResource $resource,
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Route('/api/v1/meal-courses', name: 'meal_courses_create', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['POST'])]
    public function __invoke(
        #[MapJsonApiPayload(MealCourseResource::TYPE)] CreateMealCourseDto $dto
    ): JsonApiResponse
    {
        $cmd = $this->cmdFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonApiResponse(
            $this->resource->itemReference($cmd->id),
            Response::HTTP_CREATED,
            [
                'Location' => $this->resource->selfUrl($cmd->id),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
