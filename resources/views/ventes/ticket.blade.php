<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ticket {{ $vente->numero_vente }}</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    @media print {
      @page {
        margin: 0;
        size: 80mm auto;
      }

      body {
        margin: 0;
        padding: 5mm;
      }

      .no-print {
        display: none !important;
      }
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      font-size: 11px;
      line-height: 1.5;
      color: #000;
      background: #f5f5f5;
      padding: 20px;
    }

    .ticket-container {
      max-width: 80mm;
      margin: 0 auto;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .ticket {
      padding: 15px;
    }

    /* En-tÃªte */
    .header {
      text-align: center;
      padding-bottom: 12px;
      border-bottom: 2px solid #000;
      margin-bottom: 12px;
    }

    .header h1 {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 4px;
    }

    .header .subtitle {
      font-size: 10px;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
      color: #333;
    }

    .header .address {
      font-size: 9px;
      font-weight: 400;
      color: #666;
      line-height: 1.4;
    }

    /* Infos vente */
    .info-section {
      margin-bottom: 12px;
      padding-bottom: 10px;
      border-bottom: 1px dashed #ccc;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 4px;
      font-size: 10px;
    }

    .info-row .label {
      font-weight: 500;
      color: #666;
    }

    .info-row .value {
      font-weight: 600;
      color: #000;
    }

    /* Tableau articles */
    .items-section {
      margin-bottom: 12px;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
    }

    .items-table thead th {
      font-size: 9px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 6px 0;
      border-bottom: 2px solid #000;
      text-align: left;
    }

    .items-table thead th:nth-child(2) {
      text-align: center;
      width: 50px;
    }

    .items-table thead th:nth-child(3) {
      text-align: right;
      width: 70px;
    }

    .items-table tbody td {
      padding: 8px 0;
      border-bottom: 1px solid #eee;
      vertical-align: top;
    }

    .items-table tbody tr:last-child td {
      border-bottom: none;
    }

    .item-name {
      font-weight: 600;
      font-size: 11px;
      margin-bottom: 2px;
    }

    .item-details {
      font-size: 9px;
      color: #666;
      font-weight: 400;
    }

    .item-qty {
      text-align: center;
      font-weight: 600;
      font-size: 11px;
    }

    .item-total {
      text-align: right;
      font-weight: 600;
      font-size: 11px;
    }

    /* Totaux */
    .totals-section {
      border-top: 2px solid #000;
      padding-top: 10px;
      margin-top: 10px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 6px;
      font-size: 10px;
    }

    .total-row .label {
      font-weight: 500;
      color: #666;
    }

    .total-row .value {
      font-weight: 600;
    }

    .total-row.discount {
      color: #000;
    }

    .total-row.grand-total {
      font-size: 14px;
      font-weight: 700;
      margin-top: 8px;
      padding-top: 8px;
      border-top: 2px solid #000;
    }

    .total-row.grand-total .value {
      font-size: 16px;
    }

    /* Paiement */
    .payment-section {
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px dashed #ccc;
    }

    .payment-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 5px;
      font-size: 11px;
    }

    .payment-row .label {
      font-weight: 500;
    }

    .payment-row .value {
      font-weight: 700;
    }

    .payment-row.change {
      margin-top: 6px;
      padding-top: 6px;
      border-top: 1px solid #eee;
      font-size: 12px;
    }

    /* Footer */
    .footer {
      margin-top: 15px;
      padding-top: 12px;
      border-top: 2px solid #000;
      text-align: center;
    }

    .footer-message {
      font-size: 11px;
      font-weight: 600;
      margin-bottom: 6px;
    }

    .footer-slogan {
      font-size: 10px;
      color: #666;
      margin-bottom: 10px;
    }

    .footer-meta {
      font-size: 8px;
      color: #999;
      padding-top: 8px;
      border-top: 1px dashed #ccc;
    }

    .footer-meta div {
      margin-bottom: 2px;
    }

    /* Badge statut */
    .status-badge {
      display: inline-block;
      padding: 4px 10px;
      font-size: 9px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-radius: 3px;
      margin-top: 6px;
      background: #000;
      color: #fff;
    }

    /* Bouton impression */
    .print-button {
      display: block;
      width: 100%;
      max-width: 300px;
      margin: 20px auto;
      padding: 12px;
      background: #000;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .print-button:hover {
      background: #333;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .print-button:active {
      transform: translateY(0);
    }
  </style>
</head>

<body>
  <button class="print-button no-print" onclick="window.print()">ðŸ–¨ï¸ Imprimer le Ticket</button>

  <div class="ticket-container">
    <div class="ticket">
      <!-- En-tÃªte -->
      <div class="header">
        <h1>ðŸº ETS DUBAI</h1>
        <div class="subtitle">{{ $vente->pointVente->nom }}</div>
        <div class="address">
          {{ $vente->pointVente->adresse ?? 'Sangmelima, Sud Cameroun' }}<br>
          TÃ©l: {{ $vente->pointVente->telephone ?? '+237 685 24 56 32' }}
        </div>
        <div class="status-badge">{{ $vente->statut === 'brouillon' ? 'BROUILLON' : 'VALIDÃ‰' }}</div>
      </div>

      <!-- Infos vente -->
      <div class="info-section">
        <div class="info-row">
          <span class="label">NÂ° Vente</span>
          <span class="value">{{ $vente->numero_vente }}</span>
        </div>
        <div class="info-row">
          <span class="label">Date</span>
          <span class="value">{{ $vente->date_vente->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
          <span class="label">Vendeur</span>
          <span class="value">{{ $vente->user->name }}</span>
        </div>
        @if($vente->client)
          <div class="info-row">
            <span class="label">Client</span>
            <span class="value">{{ $vente->client->nom }}</span>
          </div>
        @endif
        @if($vente->caisse)
          <div class="info-row">
            <span class="label">Caisse</span>
            <span class="value">{{ $vente->caisse->nom_caisse }}</span>
          </div>
        @endif
      </div>

      <!-- Articles -->
      <div class="items-section">
        <table class="items-table">
          <thead>
            <tr>
              <th>Article</th>
              <th>QtÃ©</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($vente->lignes as $ligne)
              <tr>
                <td>
                  <div class="item-name">{{ $ligne->produit->nom }}</div>
                  <div class="item-details">
                    {{ number_format($ligne->prix_unitaire_ttc, 0, ',', ' ') }} Ã— {{ $ligne->quantite }}
                  </div>
                </td>
                <td class="item-qty">{{ $ligne->quantite }}</td>
                <td class="item-total">{{ number_format($ligne->montant_total, 0, ',', ' ') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Totaux -->
      <div class="totals-section">
        <div class="total-row">
          <span class="label">Sous-total HT</span>
          <span class="value">{{ number_format($vente->montant_ht, 0, ',', ' ') }} FCFA</span>
        </div>
        <div class="total-row">
          <span class="label">TVA (19.25%)</span>
          <span class="value">{{ number_format($vente->montant_tva, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($vente->remise_globale > 0)
          <div class="total-row discount">
            <span class="label">Remise ({{ $vente->remise_globale }}%)</span>
            <span
              class="value">-{{ number_format($vente->montant_ht * ($vente->remise_globale / 100), 0, ',', ' ') }}</span>
          </div>
        @endif
        <div class="total-row grand-total">
          <span class="label">TOTAL</span>
          <span class="value">{{ number_format($vente->montant_total, 0, ',', ' ') }} F</span>
        </div>
      </div>

      <!-- Paiement -->
      <div class="payment-section">
        <div class="payment-row">
          <span class="label">Mode de paiement</span>
          <span class="value">
            @if($vente->type_paiement === 'especes')
              EspÃ¨ces
            @elseif($vente->type_paiement === 'mobile_money')
              Mobile Money
            @elseif($vente->type_paiement === 'credit')
              CrÃ©dit
            @else
              {{ ucfirst($vente->type_paiement) }}
            @endif
          </span>
        </div>
        <div class="payment-row">
          <span class="label">Montant payÃ©</span>
          <span class="value">{{ number_format($vente->montant_paye, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($vente->montant_rendu > 0)
          <div class="payment-row change">
            <span class="label">Monnaie rendue</span>
            <span class="value">{{ number_format($vente->montant_rendu, 0, ',', ' ') }} FCFA</span>
          </div>
        @endif
      </div>

      <!-- Footer -->
      <div class="footer">
        <div class="footer-message">Merci pour votre visite !</div>
        <div class="footer-slogan">Ã€ bientÃ´t chez ETS DUBAI</div>
        <div class="footer-meta">
          <div>Articles: {{ $vente->lignes->sum('quantite') }} | Lignes: {{ $vente->lignes->count() }}</div>
          <div>ImprimÃ© le {{ now()->format('d/m/Y Ã  H:i') }}</div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>