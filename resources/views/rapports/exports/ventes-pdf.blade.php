<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rapport de Ventes</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 11px;
      line-height: 1.4;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 15px;
    }

    .header h1 {
      font-size: 24px;
      color: #1e40af;
      margin-bottom: 5px;
    }

    .header p {
      font-size: 12px;
      color: #6b7280;
    }

    .periode {
      background: #f3f4f6;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
      text-align: center;
    }

    .stats-grid {
      display: table;
      width: 100%;
      margin-bottom: 25px;
    }

    .stat-box {
      display: table-cell;
      width: 25%;
      padding: 15px;
      text-align: center;
      border: 1px solid #e5e7eb;
      background: #f9fafb;
    }

    .stat-label {
      font-size: 10px;
      color: #6b7280;
      text-transform: uppercase;
      margin-bottom: 5px;
    }

    .stat-value {
      font-size: 18px;
      font-weight: bold;
      color: #1f2937;
    }

    .stat-unit {
      font-size: 9px;
      color: #9ca3af;
    }

    .section-title {
      font-size: 14px;
      font-weight: bold;
      color: #1f2937;
      margin: 25px 0 10px;
      padding-bottom: 5px;
      border-bottom: 1px solid #d1d5db;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table thead {
      background: #f3f4f6;
    }

    table th {
      padding: 8px;
      text-align: left;
      font-size: 10px;
      font-weight: bold;
      color: #4b5563;
      text-transform: uppercase;
      border-bottom: 2px solid #d1d5db;
    }

    table td {
      padding: 8px;
      border-bottom: 1px solid #e5e7eb;
    }

    table tbody tr:hover {
      background: #f9fafb;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .badge {
      display: inline-block;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 9px;
      font-weight: bold;
    }

    .badge-success {
      background: #d1fae5;
      color: #065f46;
    }

    .badge-info {
      background: #dbeafe;
      color: #1e40af;
    }

    .badge-warning {
      background: #fef3c7;
      color: #92400e;
    }

    .summary-box {
      background: #f0f9ff;
      border-left: 4px solid #2563eb;
      padding: 15px;
      margin: 20px 0;
    }

    .summary-box h3 {
      font-size: 13px;
      color: #1e40af;
      margin-bottom: 10px;
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      padding: 5px 0;
      border-bottom: 1px dotted #cbd5e1;
    }

    .summary-item:last-child {
      border-bottom: none;
    }

    .footer {
      margin-top: 40px;
      padding-top: 15px;
      border-top: 1px solid #d1d5db;
      text-align: center;
      font-size: 9px;
      color: #9ca3af;
    }

    .page-break {
      page-break-after: always;
    }

    .rank {
      display: inline-block;
      width: 25px;
      height: 25px;
      line-height: 25px;
      background: #dbeafe;
      color: #1e40af;
      border-radius: 50%;
      text-align: center;
      font-weight: bold;
      font-size: 11px;
    }
  </style>
</head>

<body>
  <!-- En-tête -->
  <div class="header">
    <h1>RAPPORT DE VENTES</h1>
    <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
  </div>

  <!-- Période -->
  <div class="periode">
    <strong>Période :</strong> Du {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
  </div>

  <!-- Statistiques Globales -->
  <div class="stats-grid">
    <div class="stat-box">
      <div class="stat-label">Chiffre d'Affaires</div>
      <div class="stat-value">{{ number_format($stats['ca_total'], 0, ',', ' ') }}</div>
      <div class="stat-unit">FCFA</div>
    </div>
    <div class="stat-box">
      <div class="stat-label">Nombre de Ventes</div>
      <div class="stat-value">{{ $stats['nombre_ventes'] }}</div>
      <div class="stat-unit">transactions</div>
    </div>
    <div class="stat-box">
      <div class="stat-label">Panier Moyen</div>
      <div class="stat-value">{{ number_format($stats['ca_moyen'], 0, ',', ' ') }}</div>
      <div class="stat-unit">FCFA</div>
    </div>
    <div class="stat-box">
      <div class="stat-label">Articles Vendus</div>
      <div class="stat-value">{{ number_format($stats['articles_vendus'], 0, ',', ' ') }}</div>
      <div class="stat-unit">unités</div>
    </div>
  </div>

  <!-- Répartition par Type de Paiement -->
  <div class="summary-box">
    <h3>Répartition par Type de Paiement</h3>
    <div class="summary-item">
      <span>💵 Espèces</span>
      <strong>{{ number_format($stats['especes'], 0, ',', ' ') }} FCFA</strong>
    </div>
    <div class="summary-item">
      <span>📱 Mobile Money</span>
      <strong>{{ number_format($stats['mobile_money'], 0, ',', ' ') }} FCFA</strong>
    </div>
    <div class="summary-item">
      <span>💳 Crédit</span>
      <strong>{{ number_format($stats['credit'], 0, ',', ' ') }} FCFA</strong>
    </div>
  </div>

  <!-- Performance par Vendeur -->
  @if($stats['par_vendeur']->count() > 0)
    <h2 class="section-title">Performance par Vendeur</h2>
    <table>
      <thead>
        <tr>
          <th>Vendeur</th>
          <th class="text-right">Nombre de Ventes</th>
          <th class="text-right">Chiffre d'Affaires</th>
        </tr>
      </thead>
      <tbody>
        @foreach($stats['par_vendeur'] as $vendeurStats)
          <tr>
            <td>{{ $vendeurStats['vendeur'] }}</td>
            <td class="text-right">{{ $vendeurStats['nombre'] }}</td>
            <td class="text-right"><strong>{{ number_format($vendeurStats['montant'], 0, ',', ' ') }} FCFA</strong></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- Top 10 Produits -->
  @if($topProduits->count() > 0)
    <h2 class="section-title">Top 10 Produits les Plus Vendus</h2>
    <table>
      <thead>
        <tr>
          <th style="width: 50px;" class="text-center">#</th>
          <th>Produit</th>
          <th class="text-right">Quantité</th>
          <th class="text-right">CA Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($topProduits as $index => $produit)
          <tr>
            <td class="text-center">
              <span class="rank">{{ $index + 1 }}</span>
            </td>
            <td>{{ $produit->nom }}</td>
            <td class="text-right">{{ number_format($produit->total_quantite, 0, ',', ' ') }} unités</td>
            <td class="text-right"><strong>{{ number_format($produit->total_ca, 0, ',', ' ') }} FCFA</strong></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- Saut de page -->
  <div class="page-break"></div>

  <!-- Top 10 Clients -->
  @if($topClients->count() > 0)
    <h2 class="section-title">Top 10 Clients</h2>
    <table>
      <thead>
        <tr>
          <th style="width: 50px;" class="text-center">#</th>
          <th>Client</th>
          <th class="text-right">Nombre d'Achats</th>
          <th class="text-right">CA Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($topClients as $index => $client)
          <tr>
            <td class="text-center">
              <span class="rank">{{ $index + 1 }}</span>
            </td>
            <td>{{ $client->nom }}</td>
            <td class="text-right">{{ $client->nombre_achats }}</td>
            <td class="text-right"><strong>{{ number_format($client->total_ca, 0, ',', ' ') }} FCFA</strong></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- Évolution Journalière -->
  @if($evolutionJournaliere->count() > 0)
    <h2 class="section-title">Évolution Journalière</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th class="text-right">Nombre de Ventes</th>
          <th class="text-right">Chiffre d'Affaires</th>
        </tr>
      </thead>
      <tbody>
        @foreach($evolutionJournaliere as $jour)
          <tr>
            <td>{{ \Carbon\Carbon::parse($jour->date)->format('d/m/Y') }}</td>
            <td class="text-right">{{ $jour->nombre }}</td>
            <td class="text-right"><strong>{{ number_format($jour->ca, 0, ',', ' ') }} FCFA</strong></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  <!-- Footer -->
  <div class="footer">
    <p>Document généré automatiquement par le système de gestion - {{ config('app.name') }}</p>
    <p>Page 1/1</p>
  </div>
</body>

</html>

