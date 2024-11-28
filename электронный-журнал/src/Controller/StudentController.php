<?php
namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/grade/{id}/add', name: 'app_grade_add', methods: ['POST'])]
    public function addGrade(
        Request $request,
        Student $student,
        EntityManagerInterface $em
    ): Response {
        $grade = new Grade();
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade->setStudent($student);
            $em->persist($grade);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_home');
    }
}
