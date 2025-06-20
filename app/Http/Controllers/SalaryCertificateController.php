<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class SalaryCertificateController extends Controller
{


    public function index(Request $request)
    {

        $personnel = Personnel::orderBy('Prenom_Nom')->get();

        return view('pages.salary-certificates', compact('personnel'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $matricule = $request->input('employee_name'); // This should be the Matricule value

        // Find by Matricule (primary key) - most efficient
        $foundEmployee = Personnel::find($matricule);

        $salaireData = [
            'salaire_base' => (float)$request->input('salaire_base', 0),
            'prime_residence' => (float)$request->input('prime_residence', 0),
            'heures_supp' => (float)$request->input('heures_supp', 0),
            'transport' => (float)$request->input('transport', 0),
            'allocation_fam' => (float)$request->input('allocation_fam', 0),
            'indemnite_qualif' => (float)$request->input('indemnite_qualif', 0),
            'indemnite_logement' => (float)$request->input('indemnite_logement', 0),
            'indemnite_speciale' => (float)$request->input('indemnite_speciale', 0),
            'retraite_compl' => (float)$request->input('retraite_compl', 0),
            'adi' => (float)$request->input('adi', 0),
            'retraite_rcar' => (float)$request->input('retraite_rcar', 0),
            'mutuelle' => (float)$request->input('mutuelle', 0),
            'assurance_vie' => (float)$request->input('assurance_vie', 0),
            'pret_aid' => (float)$request->input('pret_aid', 0),
            'ir' => (float)$request->input('ir', 0)
        ];

        // Calculer les totaux
        $salaireData['total_gain'] = $salaireData['salaire_base']
            + $salaireData['prime_residence']
            + $salaireData['heures_supp']
            + $salaireData['transport']
            + $salaireData['allocation_fam']
            + $salaireData['indemnite_qualif']
            + $salaireData['indemnite_logement']
            + $salaireData['indemnite_speciale']
            + $salaireData['retraite_compl']
            + $salaireData['adi'];

        $salaireData['total_retenues'] = $salaireData['retraite_rcar']
            + $salaireData['mutuelle']
            + $salaireData['assurance_vie']
            + $salaireData['pret_aid']
            + $salaireData['ir'];

        $salaireData['net_payer'] = $salaireData['total_gain'] - $salaireData['total_retenues'];

        // dd($salaireData);

        $phpWord = new PhpWord();

        // Define styles
        $titleStyle = [
            'bold' => true,
            'size' => 15,
            'alignment' => Jc::CENTER,
            'spaceAfter' => 300,
            'borderSize' => 6,
            'borderColor' => '000000',
        ];

        $bodyStyle = ['size' => 10, 'alignment' => Jc::START, 'spaceAfter' => 200];
        $referenceStyle = ['size' => 9, 'alignment' => Jc::START, 'marginLeft' => -1];

        // Content
        $section = $phpWord->addSection();
        $Year = date('Y');
        $M = date('m');
        $day = date('d');

        $header = $section->addHeader();
        $logo = public_path('logo1.png'); // Replace with the path to your logo
        $header->addImage($logo, ['width' => 200, 'height' => 100, 'alignment' => Jc::CENTER]);


        $section->addText('N/Ref : OFP/DRFM/CF/ADARISSA/N° ' . $day . '/' . $M . '/' . $Year . '                       Fes .  le ' . $foundEmployee['Etablissement'], $referenceStyle);
        $section->addTextBreak(1);

        // Add the title (ATTESTATION DE TRAVAIL)
        $section->addText('ATTESTATION DE SALAIRE', $titleStyle, array('alignment' => Jc::CENTER, 'borderSize' => 6,
        'borderColor' => '000000',));

        // Add the body text
        $section->addText('        Nous, soussignés office de la Formation Professionnelle et de la Promotion du Travail', $bodyStyle);
        $section->addText('attestons que M\' '.$foundEmployee['Prenom_Nom'].' Matricule '.$foundEmployee['Matricule'].' est employée à notre organisme en qualité de '.$foundEmployee['Fonction'].', grade CADRE PRINCIPAL et perçoit un salaire mensuel de :', $bodyStyle);

        $section->addTextBreak(1);

        // Add a table
        $tableStyle = [
            'borderSize' => 6, // Border size in points
            'borderColor' => '000000', // Black border
            'alignment' => Jc::CENTER,
        ];
        $tableHeaderStyle = [
            'size' => 9,
            'alignment' => Jc::CENTER,
        ];
        $tableCellStyle = [
            'size' => 9,
            'alignment' => Jc::CENTER,
        ];
        $tableCellStylediffrent = [
            'size' => 10,
            'alignment' => Jc::CENTER,
            'bold' => true,
        ];
        $table = $section->addTable($tableStyle, array(
            'alignment' => Jc::CENTER,
        ));

        // Add table rows
        $table->addRow();
        $table->addCell(4000)->addText('Salaire de base', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["salaire_base"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Prime de résidence', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["prime_residence"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Hrs Supplémentaire', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["heures_supp"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Indemnité de transport', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["transport"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Allocation familiale', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["allocation_fam"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Indemnité de qualification', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["indemnite_qualif"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Ind. Logement', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["indemnite_logement"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Indemnité spéciale', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["indemnite_speciale"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Retraite complémentaire', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["retraite_compl"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('A.D.I : Tranche obligatoire', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["adi"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('TOTAL GAIN', $tableCellStylediffrent, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["total_gain"], $tableCellStylediffrent, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Retraite RCAR', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["retraite_rcar"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Mutuelle (ATLANTA)', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["mutuelle"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Assurance de vie', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["assurance_vie"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('Prêt Aid El-Adha', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["pret_aid"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('I.R', $tableHeaderStyle, array('alignment' => Jc::CENTER));
        $table->addCell(2000)->addText($salaireData["ir"], $tableCellStyle, array('alignment' => Jc::CENTER));

        $table->addRow();
        $table->addCell(4000)->addText('TOTAL RETENUES', $tableCellStylediffrent, array('alignment' => Jc::CENTER,'bold' => true,));
        $table->addCell(2000)->addText($salaireData["total_retenues"], $tableCellStylediffrent, array('alignment' => Jc::CENTER,'bold' => true,));

        $table->addRow();
        $table->addCell(4000)->addText('NET A PAYER', $tableCellStylediffrent, array('alignment' => Jc::CENTER,'bold' => true,));
        $table->addCell(2000)->addText($salaireData["net_payer"], $tableCellStylediffrent, array('alignment' => Jc::CENTER,'bold' => true,));
        $section->addTextBreak(1);

        // Add the footer text
        $section->addText('- L’intéressé perçoit une prime annuelle selon son rendement.', $bodyStyle);
        $section->addText('- L’intéressé perçoit le 13ème mois.', $bodyStyle);
        $section->addText('- La présente attestation lui est délivrée pour servir et valoir ce que de droit.', $bodyStyle);
        // $section->addTextBreak(1);

        // Add the "Visa du directeur" section
        $section->addText('Visa du Directeur d’établissement', ['bold' => true, 'size' => 14, 'underline' => 'single', 'alignment' => Jc::CENTER]);

        // Add the footer
        $footer = $section->addFooter();
        $footer->addText(' Complexe de la Formation Professionnelle AL ADARISSA FES', ['size' => 8, 'alignment' => Jc::CENTER]);

        // Save the document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Attestation-de-Salaire.docx');

        return response()->download(public_path('Attestation-de-Salaire.docx'));
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
