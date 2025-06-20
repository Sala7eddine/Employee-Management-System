<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\LigneConge;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class LeaveDecisionController extends Controller
{
    public function index(Request $request)
    {

        // Use the exact parameter name from the form
        if ($request->filled('matricule')) {
            // $query->where('Matricule', $request->matricule);

            $leaveRequest = LigneConge::where('Matricule', $request->matricule);

            return view('pages.leave-decisions', [
                'conges' => $leaveRequest->orderBy('date_Demande')->paginate(10),
                'personnel' => Personnel::all(),
                'typesConge' => Conge::all(),
            ]);

            // dd($leaveRequest);
        } else {
            $leaveRequest = LigneConge::with(['personnel', 'conge'])->orderby('status');

            return view('pages.leave-decisions', [
                'conges' => $leaveRequest->orderBy('status')->paginate(10),
                'personnel' => Personnel::all(),
                'typesConge' => Conge::all(),
            ]);
        }

        // if ($request->filled('year')) {
        //     $leaveRequest->whereYear('date_Demande', $request->year);
        // } else {
        //     $leaveRequest = LigneConge::with(['personnel', 'conge']);
        // }

       
    }




    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $data_demande = $request->input('Décision');
        $leaveRequest = LigneConge::where('date_Demande', $data_demande)->first();
    

        $foundEmployee = DB::table('personnel')
            ->join('ligne_conge', 'personnel.Matricule', '=', 'ligne_conge.Matricule')
            ->where('ligne_conge.date_Demande', $data_demande)
            ->first();


            // DB::table('personnel')
            // ->where('Matricule', $foundEmployee->Matricule)
            // ->update(['Etat' => 'Inactif']);

            // dd($request->input());

        $foundEmployee_V2 = Personnel::with(['echelle.category'])->find($foundEmployee->Matricule);
    
        $date_debut = \Carbon\Carbon::parse($leaveRequest->date_D);
        $date_fin = \Carbon\Carbon::parse($leaveRequest->date_F);
        
        // Replace the simple diffInDays with working days calculation
        $nombre_jours = $this->calculateWorkingDays($date_debut, $date_fin);
        
        // Rest of your store method...
    
        $phpWord = new PhpWord();

        // Define styles
        $titleStyle = [
            'bold' => true,
            'size' => 15,
            'alignment' => Jc::CENTER,
            'spaceAfter' => 300,
            'underline' => 'single',
        ];
        $decide = [
            'bold' => true,
            'size' => 15,
            'spaceAfter' => 300,
            'underline' => 'single',
        ];
        $bodyStyle = ['size' => 12, 'alignment' => Jc::START, 'spaceAfter' => 200];
        $labelStyle = ['bold' => true, 'size' => 12];
        $valueStyle = ['size' => 12];
        $footerStyle = ['size' => 6, 'alignment' => Jc::CENTER, 'spaceBefore' => 500];
        $centerStyle = ['alignment' => Jc::CENTER, 'bold' => true, 'size' => 16];
        $referenceStyle = ['size' => 10, 'alignment' => Jc::START, 'marginLeft' => -1];

        // Content
        $section = $phpWord->addSection();
        $Year = date('Y');
        $M = date('m');
        $day = date('d');

        $header = $section->addHeader();
        $logo = public_path('logo1.png'); // Replace with the path to your logo
        $header->addImage($logo, ['width' => 200, 'height' => 100, 'alignment' => Jc::CENTER]);

        $section->addText('N/Ref : OFP/DRFM/CF/ADARISSA/N° ' . $day . '/' . $M . '/' . $Year . '                     Fes. le ' . $foundEmployee->Etablissement, $referenceStyle);
        $section->addTextBreak(1);

        // Add the title (ATTESTATION DE TRAVAIL)
        $section->addText('DECISION DU CONGÉ', $titleStyle, array('alignment' => Jc::CENTER,));
        $section->addTextBreak(1);

        // Add the body text
        $section->addText('- Le Directeur de l’Office de la Formation Professionnelle et de la Promotion du Travail', $bodyStyle);
        $section->addText('- Vu le Dahir portant loi n° 1-72-183 du Rabii II 1394 (21 MAI 1974) instituant l’Office de la Formation Professionnelle et de la Promotion du Travail.', $bodyStyle);
        $section->addText('- Vu la décision de Madame la directrice générale portant délégation de signature à Monsieur le directeur du complexe ALA ADARISSA FES.', $bodyStyle);
        $section->addText('- Vu la Demande de congé de ' . $leaveRequest->type->type_cong . ' présentée par Mr/Mme ' . $foundEmployee->Prenom_Nom . 'en date du ' . $Year . '-' . $M . '-' . $day, $bodyStyle);

        // Add the "D E C I D E" section
        $section->addTextBreak(1);
        $section->addText('D E C I D E', array('bold' => true, 'size' => 14, 'underline' => 'single', 'alignment' => Jc::CENTER), $titleStyle);

        $section->addText('ARTICLE UNIQUE :', ['underline' => 'single', 'bold' => true, 'size' => 12], $decide);

        $section->addText('      Il est accordé un congé exceptionnel à :', $bodyStyle);
        $section->addTextBreak(1);

        // Add a table with 2 rows and 2 columns
        $tableStyle = [
            'borderSize' => 6, // Border size in points
            'borderColor' => '000000', // Black border
        ];
        $table = $section->addTable($tableStyle);

        // Add the first row
        $table->addRow();
        $table->addCell(6000)->addText(' NOM  PRENOM', ['size' => 12]);
        $table->addCell(6000)->addText(' AFFECTATION', ['size' => 12]);

        // Add table rows
        $table->addRow();
        $table->addCell(2000)->addText(' Mme ' . $foundEmployee->Prenom_Nom, ['size' => 12]);
        $table->addCell(2000)->addText(' ISTA HAY AL ADARISSA', ['size' => 12]);

        $table->addRow();
        $table->addCell(2000)->addText(' CATEGORIE : ' . $foundEmployee_V2->echelle->category->Categorie, ['size' => 12]);
        $table->addCell(2000)->addText(' DUREE : ' . $nombre_jours, ['size' => 12]);

        $table->addRow();
        $table->addCell(2000)->addText(' MATRICULE : ' . $foundEmployee->Matricule, ['size' => 12]);
        $table->addCell(2000)->addText(' DATE DE DEBUT : ' . $leaveRequest->date_D, ['size' => 12]);

        $table->addRow();
        $table->addCell(2000)->addText(' ECHELLE : ' . $foundEmployee->Echelle, ['size' => 12]);
        $table->addCell(2000)->addText('DATE DE FIN : ' . $leaveRequest->date_F, ['size' => 12]);

        $table->addRow();
        $table->addCell(2000)->addText(' ECHELON : ' . $foundEmployee->echelon, ['size' => 12]);
        $table->addCell(2000)->addText(' RELIQUAT AVANT : ' . ($leaveRequest->Reliquat + $nombre_jours), ['size' => 12]);

        $table->addRow();
        $table->addCell(2000)->addText('', ['size' => 12]);
        $table->addCell(2000)->addText(' RELIQUAT APRÈS : ' . floor($leaveRequest->Reliquat), ['size' => 12]);

        // Add the "Visa du directeur" section
        $section->addTextBreak(2);
        $section->addText('Visa du Directeur d’établissement', ['bold' => true, 'size' => 12, 'underline' => 'single', 'alignment' => Jc::CENTER]);

        // Add the footer
        $footer = $section->addFooter();
        $footer->addText(' Complexe de la Formation Professionnelle AL ADARISSA FES', ['size' => 8, 'alignment' => Jc::CENTER]);

        // Save the document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Décisions-de-Congé.docx');

        session()->flash('download','true');


        if($request->input('valide')){

            DB::table('ligne_conge')
            ->where('created_at', $leaveRequest->created_at)
            ->update(['status' => 'valide']);

            return to_route('leave-decisions.index');

        }else{

            return response()->download(public_path('Décisions-de-Congé.docx'));
        }
    }


    /*
     * Calculate working days between two dates (excluding weekends)
     */
    private function calculateWorkingDays(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): int
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

    // -------------------------------------


    public function show($id)
    {
        $ligneConge = LigneConge::with(['personnel', 'conge'])->findOrFail($id);

        return view('pages.leave-decision-show', [
            'conge' => $ligneConge,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
