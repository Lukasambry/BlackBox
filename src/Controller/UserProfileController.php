<?php

namespace App\Controller;

use App\Repository\UserLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserProfileController extends AbstractController
{
    #[Route('/profile/logs', name: 'app_user_logs_export')]
    public function exportUserLogs(UserLogRepository $userLogRepository): StreamedResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $logs = $userLogRepository->findBy(['user' => $user]);

        $response = new StreamedResponse(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Id', 'Action Type', 'created_at', 'updated_at']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->getId(),
                    $log->getAction(),
                    $log->getCreatedAt()->format('Y-m-d H:i:s'),
                    $log->getUpdatedAt()->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Disposition', 'attachment; filename="user_logs.csv"');
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');

        return $response;
    }
}
