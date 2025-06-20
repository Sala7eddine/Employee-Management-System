<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PersonnelControllerInformation extends Controller
{
    public function getSalaire($matricule)
{
    $personnel = \App\Models\Personnel::with(['echelle', 'grilleIndiciaire', 'niveau'])->findOrFail($matricule);

    // Example calculations – replace with real logic if needed
    $salaireBase = $personnel->grilleIndiciaire->Salaire_de_base ?? 0;
    $primeResidence = 500;
    $heuresSupp = 0;
    $transport = 300;
    $allocationFam = ($personnel->Nombre_Enfants ?? 0) * 200;
    $qualification = 400;
    $logement = 600;
    $speciale = 300;
    $retraiteCompl = $salaireBase * 0.07;
    $adi = $salaireBase * 0.02;

    $rcar = $salaireBase * 0.06;
    $mutuelle = 150;
    $assuranceVie = $personnel->Assurance_Vie ?? 0;
    $pretAid = 0;
    $ir = $salaireBase * 0.15;

    return response()->json([
        'salaire_base' => $salaireBase,
        'prime_residence' => $primeResidence,
        'heures_supp' => $heuresSupp,
        'transport' => $transport,
        'allocation_fam' => $allocationFam,
        'indemnite_qualif' => $qualification,
        'indemnite_logement' => $logement,
        'indemnite_speciale' => $speciale,
        'retraite_compl' => $retraiteCompl,
        'adi' => $adi,
        'retraite_rcar' => $rcar,
        'mutuelle' => $mutuelle,
        'assurance_vie' => $assuranceVie,
        'pret_aid' => $pretAid,
        'ir' => $ir,
    ]);
}

}
