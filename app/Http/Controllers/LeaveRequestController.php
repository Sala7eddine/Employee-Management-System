<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\LigneConge;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\SimpleType\Jc;
use App\Services\LeaveService;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $personnel = Personnel::orderBy('Prenom_Nom')->get();
        return view('pages.leave-requests', compact('personnel'));
        
    }

    public function create() {}


    public function store(Request $request)
    {
        $validated = $request->validate([
            'NatureConge' => 'required|string',
            'nombre_jours' => 'required|integer|min:1',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'employee_name' => 'required|exists:personnel,Matricule'
        ]);

        $matricule = $validated['employee_name'];
        $foundEmployee = Personnel::with(['echelle.category'])->find($matricule);

        return DB::transaction(function () use ($validated, $foundEmployee) {
            // 1. Get current year balance
            $currentYear = date('Y');
            $lastLeave = DB::table('ligne_conge')
                ->where('Matricule', $validated['employee_name'])
                ->whereBetween('date_Demande', [
                    strtotime("$currentYear-01-01"),
                    strtotime("$currentYear-12-31 23:59:59")
                ])
                ->latest('date_Demande')
                ->first();

            // $currentInterval = date('s'); // Changes every minute
            // $matricule = $validated['employee_name'];

            // // Get balance for current "interval" (simulated year)
            // $lastLeave = DB::table('ligne_conge')
            //     ->where('Matricule', $matricule)
            //     ->whereBetween('date_Demande', [
            //         now()->setSeconds($currentInterval)->startOfMinute()->timestamp,
            //         now()->setSeconds($currentInterval)->endOfMinute()->timestamp
            //     ])
            //     ->latest('date_Demande')
            //     ->first();

            $reliquatAvant = $lastLeave ? $lastLeave->Reliquat : 30;
            $reliquatApres = $reliquatAvant - $validated['nombre_jours'];

            // dd();

            // 2. Validate balance
            // if ($reliquatApres < 0) {
            //     throw new \Exception("Solde insuffisant. Reliquat disponible: $reliquatAvant jours");
            // }

            // 3. Create records

            if($reliquatApres > 0){
                $numCong = DB::table('conge')->insertGetId([
                    'type_cong' => $validated['NatureConge'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                DB::table('ligne_conge')->insert([
                'date_Demande' => now()->timestamp,
                'date_D' => $validated['date_debut'],
                'date_F' => $validated['date_fin'],
                'Reliquat' => $reliquatApres,
                'Matricule' => $validated['employee_name'],
                'status' => 'en attente',
                'num_cong' => $numCong,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
        }
            // 4. Generate document
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            // Header with logo
            $header = $section->addHeader();
            $header->addImage(public_path('logo1.png'), [
                'width' => 200,
                'height' => 100,
                'alignment' => Jc::CENTER
            ]);
            $titleStyle = [
                'bold' => true,
                'size' => 15,
                'alignment' => Jc::CENTER,
                'spaceAfter' => 300,
                'underline' => 'single',
            ];

            // Document title
            $section->addText('DEMANDE DE CONGE', $titleStyle, array('alignment' => Jc::CENTER,));

            // Employee details
            $section->addTextBreak(1);
            $section->addText('Nom et Prénom : ' . $foundEmployee->Prenom_Nom . '   Matricule : ' . $foundEmployee->Matricule, ['bold' => true]);
            $section->addText('Catégorie : ' . ($foundEmployee->echelle->category->Categorie ?? 'N/A'), ['bold' => true]);
            $section->addText('Fonction : ' . $foundEmployee->Fonction, ['bold' => true]);
            $section->addText('Affectation : ' . $foundEmployee->Etablissement, ['bold' => true]);
            $section->addTextBreak(1);

            // Leave details
            $section->addText('Nature de congé : ' . $validated['NatureConge'] . '   Nombre de jours : ' . $validated['nombre_jours'], ['bold' => true]);
            $section->addText('A compter du : ' . $validated['date_debut'] . '   Au : ' . $validated['date_fin'], ['bold' => true]);
            $section->addTextBreak(1);

            // Reliquat table
            $table = $section->addTable(['borderSize' => 6]);
            $table->addRow();
            $table->addCell(10000)->addText(
                'RELIQUAT (' . $currentYear . ') AVANT LA DEMANDE : ' . floor($reliquatAvant) . ' JOURS',
                ['bold' => true, 'size' => 12, 'alignment' => Jc::CENTER]
            );
            $table->addRow();
            $table->addCell(10000)->addText(
                'RELIQUAT (' . $currentYear . ') APRES ACCEPTATION : ' . $reliquatApres . ' JOURS',
                ['bold' => true, 'size' => 12, 'alignment' => Jc::CENTER]
            );

            // Footer
            $section->addTextBreak(2);
            $section->addText("SIGNATURE DE L'INTERESSE(E)                   AVIS DE CHEF IMMEDIAT", ['bold' => true]);
            $section->addTextBreak(3);
            $section->addText('(*) NATURE DE CONGE:', ['bold' => true]);
            $section->addText('• Administratif • Mariage • Naissance • Exceptionnel');

            $footer = $section->addFooter();
            $footer->addText('CF ADARISSA FES', ['size' => 8]);

            // Save and return
            if($reliquatApres < 0){
                return to_route('leave-requests.index')->with('invalideConge','solde indisponible');
            }else{
                $fileName = 'Demande-Conge-' . $foundEmployee->Matricule . '-' . now()->format('Ymd-His') . '.docx';
                $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($fileName);
                return response()->download($fileName)->deleteFileAfterSend(true);
                // return to_route('leave-requests.index')->with('valideConge','La demande de congé a été ajoutée avec succès.');
            }

            
        });
    }

    public function show($id)
    {
        //
    }

    public function store2(Request $request)
    {
        try {
            // 1. Validate input
            $data_demande = $request->input('PDF');
            if (!$data_demande) {
                throw new \Exception('Date demande is required');
            }

            // 2. Find the leave request and person
            $leaveRequest = LigneConge::where('date_Demande', $data_demande)->first();
            if (!$leaveRequest) {
                throw new \Exception('Leave request not found');
            }

            $person = DB::table('personnel')
                ->join('ligne_conge', 'personnel.Matricule', '=', 'ligne_conge.Matricule')
                ->where('ligne_conge.date_Demande', $data_demande)
                ->first();

            if (!$person) {
                throw new \Exception('Personnel not found');
            }

            // 3. Calculate leave duration and remaining balance
            $startDate = \Carbon\Carbon::parse($leaveRequest->date_D);
            $endDate = \Carbon\Carbon::parse($leaveRequest->date_F);
            $leaveDays =  $this->calculateWorkingDays1($startDate, $endDate);
            $currentYear = now()->year;

            // 4. Generate document
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            // Header with logo
            $header = $section->addHeader();
            $header->addImage(public_path('logo1.png'), [
                'width' => 200,
                'height' => 100,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
            ]);
            $titleStyle = [
                'bold' => true,
                'size' => 15,
                'alignment' => Jc::CENTER,
                'spaceAfter' => 300,
                'underline' => 'single',
            ];

            // Document title
            $section->addText('DEMANDE DE CONGE',$titleStyle , array(
                'bold' => true,
                'size' => 17,
                'alignment' => 'center',
                'underline' => 'single',
                'spaceAfter' => 300
            ));

            // Employee details
            $section->addTextBreak(1);
            $section->addText('Nom et Prénom : ' . $person->Prenom_Nom . '   Matricule : ' . $person->Matricule, ['bold' => true]);
            $section->addText('Catégorie : ' . ($person->Echelle ?? 'N/A'), ['bold' => true]);
            $section->addText('Fonction : ' . $person->Fonction, ['bold' => true]);
            $section->addText('Affectation : ' . ($person->Etablissement ?? 'N/A'), ['bold' => true]);
            $section->addTextBreak(1);

            // Leave details
            $section->addText('Nature de congé : ' . $leaveRequest->type->type_cong .                '   Nombre de jours : ' . $leaveDays, ['bold' => true]);
            $section->addText('A compter du : ' . $leaveRequest->date_D . '   Au : ' . $leaveRequest->date_F, ['bold' => true]);
            $section->addTextBreak(1);

            // Reliquat table
            $table = $section->addTable(['borderSize' => 6]);
            $table->addRow();
            $table->addCell(10000)->addText(
                'RELIQUAT (' . $currentYear . ') AVANT LA DEMANDE : ' . ($leaveRequest->Reliquat + $leaveDays) . ' JOURS',
                ['bold' => true, 'size' => 12, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );
            $table->addRow();
            $table->addCell(10000)->addText(
                'RELIQUAT (' . $currentYear . ') APRES ACCEPTATION : ' . floor($leaveRequest->Reliquat) . ' JOURS',
                ['bold' => true, 'size' => 12, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
            );

            // Footer
            $section->addTextBreak(2);
            $section->addText("SIGNATURE DE L'INTERESSE(E)                   AVIS DE CHEF IMMEDIAT", ['bold' => true]);
            $section->addTextBreak(3);
            $section->addText('(*) NATURE DE CONGE:', ['bold' => true]);
            $section->addText('• Administratif • Mariage • Naissance • Exceptionnel');

            $footer = $section->addFooter();
            $footer->addText('CF ADARISSA FES', ['size' => 8]);

            // Save and return
            $fileName = 'Demande-Conge-' . $person->Matricule . '-' . now()->format('Ymd-His') . '.docx';
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save(storage_path('app/public/' . $fileName));

            return response()->download(storage_path('app/public/' . $fileName))->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating document: ' . $e->getMessage());
        }
    }


    /*
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays1(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): int
    {
        $totalDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Skip Saturdays (6) and Sundays (0)
            if (!in_array($currentDate->dayOfWeek, [\Carbon\Carbon::SATURDAY, \Carbon\Carbon::SUNDAY])) {
                $totalDays++;
            }
            $currentDate->addDay();
        }

        return $totalDays;
    }


    public function edit(Request $request)
    {
        $leaveRequest = LigneConge::findOrFail($request->update);

        return view('pages.edit', [
            'leaveRequest' => $leaveRequest,
            'personnel' => Personnel::all(),
            'typesConge' => Conge::all()
        ]);
    }


    
        public function update(Request $request, $date_Demande)
        {
            $validated = $request->validate([
                'date_D' => 'required|date',
                'date_F' => 'required|date|after_or_equal:date_D',
                'Matricule' => 'required|exists:personnel,Matricule',
                'nombre_jours' => 'required|integer|min:1',
                'type_cong' => 'required|string|in:Administratif,Mariage,Naissance,Exceptionnel',
                'num_cong' => 'required|string'
            ]);
    
            return DB::transaction(function () use ($validated, $date_Demande) {
                $leaveRequest = LigneConge::where('date_Demande', $date_Demande)
                                  ->where('Matricule', $validated['Matricule'])
                                  ->firstOrFail();

            // dd($leaveRequest->conge);
                
    
                $newWorkingDays = $this->calculateWorkingDays($validated['date_D'], $validated['date_F']);
                
                if ($newWorkingDays != $validated['nombre_jours']) {
                    return back()->withErrors(['date_F' => 'Working days calculation mismatch']);
                }
    
                $oldWorkingDays = $this->calculateWorkingDays($leaveRequest->date_D, $leaveRequest->date_F);
                $daysDifference = $newWorkingDays - $oldWorkingDays;
    
                $leaveRequest->update([
                    'date_D' => $validated['date_D'],
                    'date_F' => $validated['date_F'],
                    'nombre_jours' => $newWorkingDays,
                    'num_cong' => $leaveRequest->num_cong,
                ]);
    
                // if ($leaveRequest->conge) {
                    $leaveRequest->conge->update(['type_cong' => $leaveRequest->conge['type_cong']]);
                // }
    
                if ($daysDifference != 0) {
                    $this->updateSubsequentLeaves($validated['Matricule'], $date_Demande, $daysDifference);
                }
    
                return redirect()->route('leave-decisions.index')
                    ->with('success', 'Leave request updated successfully');
            });
        }
    
        /**
         * Calculate working days between two dates (excluding weekends)
         */
        protected function calculateWorkingDays($startDate, $endDate)
        {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            
            return $start->diffInDaysFiltered(function (Carbon $date) {
                return !$date->isWeekend();
            }, $end) + 1; // +1 to include both start and end dates
        }
    
        /**
         * Update subsequent leaves' balance
         */
        protected function updateSubsequentLeaves($matricule, $currentDate, $daysDifference)
        {
            $currentYear = Carbon::parse($currentDate)->year;
            
            LigneConge::where('Matricule', $matricule)
                ->where('date_Demande', '>', $currentDate)
                ->whereYear('date_Demande', $currentYear)
                ->orderBy('date_Demande')
                ->each(function ($leave) use ($daysDifference) {
                    $leave->Reliquat -= $daysDifference;
                    $leave->save();
                });
        }
    

    public function destroy(Request $request)
    {
        $request->validate([
            'date_Demande' => 'required'
        ]);

        $leaveRequest = LigneConge::findOrFail($request->date_Demande);
        $conge = Conge::findOrFail($leaveRequest->num_cong);
        $leaveRequest->delete();
        $conge->delete();


        return redirect()->route('leave-decisions.index')->with('success_sup', 'Demande supprimée avec succès');
    }
}
