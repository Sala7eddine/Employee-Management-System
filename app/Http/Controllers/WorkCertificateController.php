<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\SimpleType\Jc;

class WorkCertificateController extends Controller
{

    public function index(Request $request)
    {


        // $personnel = Personnel::all();
        $personnel = Personnel::orderBy('Prenom_Nom')->get();

        return view('pages.work-certificates', compact('personnel'));
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



        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Define styles
        $titleStyle = [
            'bold' => true,
            'size' => 17,
            'alignment' => 'center',
            'spaceAfter' => 300,
            'borderSize' => 6,
            'borderColor' => '000000',
        ];
        $bodyStyle = ['size' => 12, 'alignment' => 'left', 'spaceAfter' => 200];
        $labelStyle = ['bold' => true, 'size' => 12];
        $valueStyle = ['size' => 12];
        $footerStyle = ['size' => 9, 'alignment' => Jc::CENTER, 'spaceBefore' => 500];
        $centerStyle = ['alignment' => Jc::CENTER, 'bold' => true, 'size' => 16];
        $referenceStyle = ['size' => 10, 'alignment' => Jc::START, 'marginLeft' => -1];
        $border = [
            'borderSize' => 6,
            'borderColor' => '000000',
        ];


        // Content
        $section = $phpWord->addSection();
        $Year = date('Y');
        $M = date('m');
        $day = date('d');

        $header = $section->addHeader();
        $logo = public_path('logo1.png'); // Replace with the path to your logo
        $header->addImage($logo, ['width' => 200, 'height' => 100, 'alignment' => Jc::CENTER]);

        $section->addText('N/Ref : OFP/DRFM/CF/ADARISSA/N° ' . $day . '/' . $M . '/' . $Year . '                     Fes. le ' . $foundEmployee['Etablissement'], $referenceStyle);
        $section->addTextBreak(1);

        // Add the title (ATTESTATION DE TRAVAIL)
        $section->addText('ATTESTATION DE TRAVAIL', $titleStyle, array(
            'borderSize' => 3,
            'borderColor' => '000000',
            'alignment' => 'center',
            'cellMargin' => 50
        ));
        $section->addTextBreak(1);

        // Add the body text
        $section->addText('Nous soussignés, Office de la Formation Professionnelle et de la Promotion du Travail, attestons que,', $bodyStyle);
        $section->addTextBreak(2);

        // Add employee details
        $section->addText('Mr                       :  ' . $foundEmployee['Prenom_Nom'], $labelStyle);

        $section->addText('Matricule             :  ' . $foundEmployee['Matricule'], $labelStyle);

        $section->addText('Grade                  :  ' . $foundEmployee['Echelle'], $labelStyle);
        $section->addTextBreak(1);

        // Add employment details
        $section->addText('Est employé (e) au sein de nos services :', $bodyStyle);

        $section->addText('En qualité de             :  ' . $foundEmployee['NIV'], $labelStyle);

        $section->addText('Lieu                         :    ISTA HAY AL ADARISSA FES', $labelStyle);

        $section->addText('Numero de Telephone  :  0514502213', $labelStyle);
        $section->addTextBreak(1);

        // Add the final note
        $section->addText('La présente attestation est délivrée à l’intéressé pour servir et valoir ce que de droit.', $bodyStyle);
        $section->addTextBreak(2);

        // Add the director's visa section
        $section->addText('Visa du Directeur d’établissement', ['bold' => true, 'size' => 14, 'alignment' => 'center', 'underline' => 'single'], $centerStyle);
        $section->addTextBreak(2);

        // Add the footer
        $footer = $section->addFooter();
        $footer->addText(' Complexe de la Formation Professionnelle AL ADARISSA FES', ['size' => 8, 'alignment' => Jc::CENTER]);

        // Save the document
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('attestation-de-travail.docx');

        return response()->download(public_path('attestation-de-travail.docx'));

        return redirect()->route('work-certificates.index')->with('success', 'Work certificate generated successfully!');

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
