<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use App\Form\GradeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GradeController extends AbstractController
{
    #[Route('/grade/add/{id}', name: 'app_grade_add')]
    public function addGrade(
        Student $student, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        // Создаём новую оценку, привязываем её к студенту
        $grade = new Grade();
        $grade->setStudent($student);

        // Создаём форму
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Сохраняем в базу
            $entityManager->persist($grade);
            $entityManager->flush();

            // Редирект после успешной отправки
            return $this->redirectToRoute('app_student_list'); // Или другая страница
        }

        // Рендерим форму
        return $this->render('grade/add.html.twig', [
            'form' => $form->createView(),
            'student' => $student,
        ]);
    }
}
