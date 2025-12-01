<?php

namespace App\Http\Controllers;

use App\Models\Ristourne;
use App\Models\BaremeRistourne;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RistourneController extends Controller
{
    /**
     * Afficher la liste des ristournes
     */
    public function index(Request $request)
    {
        $query = Ristourne::with('fournisseur');

        // Filtre par fournisseur
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par période
        if ($request->filled('annee')) {
            $query->whereYear('date_debut', $request->annee);
        }

        // Tri
        $sortField = $request->get('sort', 'date_debut');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $ristournes = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = Ristourne::statistiquesGlobales();

        // Fournisseurs pour le filtre
        $fournisseurs = Fournisseur::actif()->orderBy('nom')->get();

        return view('ristournes.index', compact('ristournes', 'stats', 'fournisseurs'));
    }

    /**
     * Afficher le formulaire de calcul
     */
    public function create()
    {
        $fournisseurs = Fournisseur::actif()
            ->with('baremes')
            ->orderBy('nom')
            ->get()
            ->filter(function ($fournisseur) {
                return $fournisseur->baremes->count() > 0;
            });

        return view('ristournes.create', compact('fournisseurs'));
    }

    /**
     * Calculer les ristournes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'type_periode' => 'required|in:mensuel,trimestriel,semestriel,annuel',
            'date_reference' => 'required|date',
        ]);

        $fournisseur = Fournisseur::findOrFail($validated['fournisseur_id']);
        $dateRef = Carbon::parse($validated['date_reference']);

        // Calculer les dates selon le type de période
        [$dateDebut, $dateFin, $periodeLibelle] = $this->calculerPeriode(
            $validated['type_periode'],
            $dateRef
        );

        // Calculer la ristourne
        $ristourne = Ristourne::calculerPourFournisseur(
            $fournisseur->id,
            $dateDebut,
            $dateFin,
            $periodeLibelle
        );

        if ($ristourne->montant_ristourne > 0) {
            return redirect()->route('ristournes.show', $ristourne)
                ->with('success', "Ristourne calculée : {$ristourne->montant_ristourne} FCFA");
        } else {
            return back()->with('warning', 'Aucune ristourne calculée pour cette période. Le seuil minimum n\'est peut-être pas atteint.');
        }
    }

    /**
     * Afficher les détails d'une ristourne
     */
    public function show(Ristourne $ristourne)
    {
        $ristourne->load(['fournisseur', 'fournisseur.baremes']);

        // Récupérer les commandes de la période
        $commandes = DB::table('commandes')
            ->where('fournisseur_id', $ristourne->fournisseur_id)
            ->where('statut', 'livree')
            ->whereBetween('date_livraison_reelle', [$ristourne->date_debut, $ristourne->date_fin])
            ->orderBy('date_livraison_reelle', 'desc')
            ->get();

        return view('ristournes.show', compact('ristourne', 'commandes'));
    }

    /**
     * Valider une ristourne
     */
    public function valider(Ristourne $ristourne)
    {
        if ($ristourne->valider()) {
            return back()->with('success', 'Ristourne validée avec succès.');
        }

        return back()->with('error', 'Impossible de valider cette ristourne.');
    }

    /**
     * Marquer une ristourne comme payée
     */
    public function payer(Request $request, Ristourne $ristourne)
    {
        $validated = $request->validate([
            'date_paiement' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($ristourne->marquerPayee($validated['date_paiement'])) {
            if (isset($validated['notes'])) {
                $ristourne->notes = $validated['notes'];
                $ristourne->save();
            }

            return redirect()->route('ristournes.show', $ristourne)
                ->with('success', 'Ristourne marquée comme payée.');
        }

        return back()->with('error', 'Impossible de marquer cette ristourne comme payée.');
    }

    /**
     * Annuler une ristourne
     */
    public function annuler(Request $request, Ristourne $ristourne)
    {
        $validated = $request->validate([
            'motif' => 'required|string|max:500',
        ]);

        if ($ristourne->annuler($validated['motif'])) {
            return redirect()->route('ristournes.index')
                ->with('success', 'Ristourne annulée.');
        }

        return back()->with('error', 'Impossible d\'annuler cette ristourne.');
    }

    /**
     * Simulateur de ristournes
     */
    public function simulateur()
    {
        $fournisseurs = Fournisseur::actif()
            ->with('baremes')
            ->has('baremes')
            ->orderBy('nom')
            ->get();

        return view('ristournes.simulateur', compact('fournisseurs'));
    }

    /**
     * Calculer simulation
     */
    public function simulation(Request $request)
    {
        $validated = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_achats' => 'required|numeric|min:0',
        ]);

        $fournisseur = Fournisseur::with('baremes')->findOrFail($validated['fournisseur_id']);
        $montantAchats = $validated['montant_achats'];

        // Trouver le barème applicable
        $baremeApplicable = $fournisseur->baremes()
            ->actif()
            ->where('seuil_min', '<=', $montantAchats)
            ->where(function ($q) use ($montantAchats) {
                $q->whereNull('seuil_max')
                    ->orWhere('seuil_max', '>=', $montantAchats);
            })
            ->orderBy('taux', 'desc')
            ->first();

        $montantRistourne = 0;
        $tauxApplique = 0;

        if ($baremeApplicable) {
            $tauxApplique = $baremeApplicable->taux;
            $montantRistourne = $montantAchats * ($tauxApplique / 100);
        }

        // Calculer les paliers suivants
        $prochainspaliers = $fournisseur->baremes()
            ->actif()
            ->where('seuil_min', '>', $montantAchats)
            ->orderBy('seuil_min')
            ->limit(3)
            ->get()
            ->map(function ($bareme) use ($montantAchats) {
                $aAjouter = $bareme->seuil_min - $montantAchats;
                $ristournePotentielle = $bareme->seuil_min * ($bareme->taux / 100);
                $gainSupplementaire = $ristournePotentielle - ($montantAchats * ($bareme->taux / 100));

                return [
                    'seuil' => $bareme->seuil_min,
                    'taux' => $bareme->taux,
                    'a_ajouter' => $aAjouter,
                    'ristourne_potentielle' => $ristournePotentielle,
                    'gain_supplementaire' => $gainSupplementaire,
                ];
            });

        return response()->json([
            'montant_achats' => $montantAchats,
            'bareme_applicable' => $baremeApplicable ? [
                'nom' => $baremeApplicable->nom,
                'seuil_min' => $baremeApplicable->seuil_min,
                'seuil_max' => $baremeApplicable->seuil_max,
                'taux' => $baremeApplicable->taux,
            ] : null,
            'taux_applique' => $tauxApplique,
            'montant_ristourne' => $montantRistourne,
            'prochains_paliers' => $prochainspaliers,
        ]);
    }

    /**
     * Calculer la période selon le type
     */
    private function calculerPeriode($type, Carbon $dateRef)
    {
        switch ($type) {
            case 'mensuel':
                $debut = $dateRef->copy()->startOfMonth();
                $fin = $dateRef->copy()->endOfMonth();
                $libelle = $dateRef->format('F Y');
                break;

            case 'trimestriel':
                $trimestre = ceil($dateRef->month / 3);
                $debut = $dateRef->copy()->month(($trimestre - 1) * 3 + 1)->startOfMonth();
                $fin = $debut->copy()->addMonths(2)->endOfMonth();
                $libelle = "T{$trimestre} {$dateRef->year}";
                break;

            case 'semestriel':
                $semestre = $dateRef->month <= 6 ? 1 : 2;
                $debut = $dateRef->copy()->month($semestre == 1 ? 1 : 7)->startOfMonth();
                $fin = $debut->copy()->addMonths(5)->endOfMonth();
                $libelle = "S{$semestre} {$dateRef->year}";
                break;

            case 'annuel':
                $debut = $dateRef->copy()->startOfYear();
                $fin = $dateRef->copy()->endOfYear();
                $libelle = "Année {$dateRef->year}";
                break;

            default:
                $debut = $dateRef->copy()->startOfMonth();
                $fin = $dateRef->copy()->endOfMonth();
                $libelle = $dateRef->format('F Y');
        }

        return [$debut, $fin, $libelle];
    }
}
