<?php
// src/Controller/CalendarController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DateTimeZone;

class CalendarController extends AbstractController
{
    #[Route('/calendar/{type}/{month?}', name: 'calendar', defaults: ['type' => 'table', 'month' => null])]
    public function index(string $type, ?int $month): Response
    {
        // Если месяц не указан, используем текущий месяц
        $currentMonth = $month ?? (int) (new DateTime('now', new DateTimeZone('UTC')))->format('m');
        $currentYear = (int) (new DateTime('now', new DateTimeZone('UTC')))->format('Y');
        
        // Получение массива с днями месяца
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = new DateTime("$currentYear-$currentMonth-$day");
            $days[] = [
                'date' => $date,
                'isWeekend' => in_array($date->format('N'), [6, 7]) // 6 - Суббота, 7 - Воскресенье
            ];
        }

        // Определяем шаблон в зависимости от параметра $type
        $template = match ($type) {
            'list' => 'calendar/list.html.twig',
            'weekends' => 'calendar/weekends.html.twig',
            default => 'calendar/table.html.twig',
        };

        return $this->render($template, [
            'days' => $days,
            'month' => $currentMonth,
            'year' => $currentYear
        ]);
    }
}
