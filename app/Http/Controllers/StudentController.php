<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\ClassResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\StudentResource;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;

class StudentController extends Controller
{
    /**
     * Jelenítse meg az erőforrás listáját.
     *
     * Ez a funkció lekéri a tanulók lapszámozott listáját a hozzájuk tartozó osztályokkal.
     * A keresési lekérdezési karakterláncot is tartalmazza a válaszban, ha létezik.
     *
     * @param Request $request A HTTP kérés objektuma.
     * @return Response Az ŰInertia válaszobjektum.
     */
    public function index(Request $request)
    {
        // Keresse le a tanulókat az alkalmazott keresési lekérdezéssel.
        $studentQuery = Student::search($request);
        // Töltse le az összes osztályt.
        $classes = ClassResource::collection(Classes::all());

        $sections = SectionResource::collection(Section::all());
        
        // Visszatérés az Inertia nézethez a lapszámozott tanulókkal és osztályokkal.
        // A keresési lekérdezési karakterláncot is tartalmazza a válaszban, ha létezik.
        return inertia('Student/Index', [
            'students' => StudentResource::collection(
                $studentQuery->paginate(5)
            ),
            'classes' => $classes,
            'sections' => $sections,
            'search' => request('search') ?? ''
        ]);
    }

    /**
     * Alkalmazzon keresési szűrőt az adott lekérdezésre.
     *
     * Ez a függvény egy keresési szűrőt alkalmaz az adott lekérdezéskészítő objektumra.
     * Ellenőrzi, hogy a keresési paraméter szerepel-e a kérésben, és ha igen,
     * egy where záradékot ad a lekérdezéshez a tanulók név szerinti szűréséhez.
     *
     * @param Builder $query A lekérdezéskészítő objektum.
     * @param string|null $search A keresési paraméter a kérésből.
     * @return Builder A módosított lekérdezéskészítő objektum.
     */
    protected function applySearch(Builder $query, $search)
    {
        // Ha a keresési paraméter megvan, adjon hozzá egy where záradékot a tanulók név szerinti szűréséhez.
        return $query->when($search, function ($query) use($search) {
            $query->where('name', 'like', "%{$search}%");
        });
    }

    /**
     * Hozza létre egy új tanulói rekord létrehozására szolgáló oldalt.
     *
     * @return Response A tanuló létrehozása oldal.
     */
    public function create()
    {
        // Összegyűjti az összes osztályt adatbázisból, amelyeket a student/create oldalon kell használni.
        $classes = ClassResource::collection(Classes::all());

        // A visszatérési érték egy inertia oldal, amely a student/create sablont használja és az összes
        // osztályt tartalmazza a visszatérési értékben.
        return inertia('Student/Create', [
            // Az összes osztályt egy osztály erőforrás objektumban jelöli.
            'classes' => $classes
        ]);
    }

    /**
     * Hozza létre egy új tanulói rekordot az adatbázisban.
     *
     * @param StoreStudentRequest $request A tanulói rekord létrehozásához használt érvényesítéses kérés objektum.
     * @return RedirectResponse Átirányítási válasz a tanulók indexoldalára.
     */
    public function store(StoreStudentRequest $request)
    {
        // Hozza létre a tanulói rekordot az adatbázisban a kérés érvényesítésével.
        Student::create($request->validated());

        // Átirányítása a tanulók indexoldalára
        return redirect()->route('students.index');
    }

    /**
     * Nyújt egy tanulói rekord szerkesztésére szolgáló oldalat.
     *
     * @param Student $student A szerkesztendő tanulói rekord.
     * @return Response A tanuló szerkesztésére szolgáló oldal.
     */
    public function edit(Student $student)
    {
        // Összegyűjti az összes osztályt adatbázisból, amelyeket a student/edit oldalon kell használni.
        $classes = ClassResource::collection(Classes::all());

        // A visszatérési érték egy inertia oldal, amely a student/edit sablont használja és a tanulót
        // és az összes osztályt tartalmazza a visszatérési értékben.
        return inertia('Student/Edit', [
            // A tanuló rekordját egy tanuló erőforrás objektumban jelöli.
            'student' => StudentResource::make($student),
            // Az összes osztályt egy osztály erőforrás objektumban jelöli.
            'classes' => $classes
        ]);
    }

    /**
     * Frissítse a megadott tanulói rekordot az adatbázisban.
     *
     * @param UpdateStudentRequest $request Az érvényesített frissítési kérés objektum.
     * @param Student $student A frissítendő tanulói rekord.
     * @return RedirectResponse Átirányítási válasz a tanulók indexoldalára.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        // Frissítse a tanulói rekordot az adatbázisban
        $student->update($request->validated());

        // Átirányítása a tanulók indexoldalára
        return redirect()->route('students.index');
    }

    /**
     * Töröl egy tanulói rekordot az adatbázisból.
     *
     * @param Student $student A törlendő tanulói rekord.
     * @return RedirectResponse Átirányítási válasz a tanulók indexoldalára.
     */
    public function destroy(Student $student)
    {
        // Törölje a hallgatói rekordot az adatbázisból
        $student->delete();

        // A felhasználó átirányítása a tanulók indexoldalára
        return redirect()->route('students.index');
    }
}
