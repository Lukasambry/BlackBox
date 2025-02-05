<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class JsonSerializerController extends AbstractController
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function respond(mixed $data, int $status = Response::HTTP_OK): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json');
        return new JsonResponse($json, $status, [], true);
    }
}
