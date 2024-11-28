<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Grade;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
    #[Route('/students', name: 'app_student_index')]
    public function index(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/student/{id}/add-grade', name: 'app_student_add_grade')]
    public function addGrade(int $id, Request $request, StudentRepository $studentRepository, EntityManagerInterface $entityManager): Response
    {
        $student = $studentRepository->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Студент не найден');
        }

        $gradeValue = $request->request->get('grade');
        if ($gradeValue) {
            $grade = new Grade();
            $grade->setGrade((int)$gradeValue);
            $grade->setStudent($student);

            $entityManager->persist($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_student_index');
    }
}
