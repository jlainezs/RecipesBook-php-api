<?php
namespace App\MealCourse\Presentation\Http\Resource;

use App\MealCourse\Application\Query\MealCourse\MealCourseDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class MealCourseResource
{
    public const string TYPE = 'meal_course';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('meal_courses_get_instance', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            'meal_courses_list',
            ['page' => ['offset' => $offset, 'limit' => $limit]],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resourceObject(MealCourseDto $mealCourseDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $mealCourseDto->id,
            'attributes' => [
                'name' => $mealCourseDto->name,
                'createdAt' => $mealCourseDto->createdAt,
                'updatedAt' => $mealCourseDto->updatedAt,
            ],
            'links' => [
                'self' => $this->selfUrl($mealCourseDto->id),
            ],
        ];
    }

    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl($offset, $limit),
                'first' => $this->listUrl(0, $limit),
                'prev' => $offset > 0 ? $this->listUrl(max(0, $offset - $limit), $limit) : null,
                'next' => $limit > 0 && count($items) === $limit ? $this->listUrl($offset + $limit, $limit) : null,
            ],
            'data' => array_map($this->resourceObject(...), $items)
        ];
    }

    public function item(MealCourseDto $mealCourseDto): array
    {
        return [
            'links' => ['self' => $this->selfUrl($mealCourseDto->id)],
            'data' => $this->resourceObject($mealCourseDto)
        ];
    }

    public function itemReference(string $id): array
    {
        return [
            'links' => ['self' => $this->selfUrl($id)],
            'data' => [
                'type' => self::TYPE,
                'id' => $id,
                'links' => ['self' => $this->selfUrl($id)],
                'attributes' => [
                    'id' => $id,
                ]
            ],
        ];
    }
}
