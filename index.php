<?php
// ---------------------------
// Helpers
// ---------------------------
function toFloat($v) {
  if ($v === null || $v === '') return 0.0;
  // troca vírgula por ponto para aceitar "123,45"
  return (float)str_replace(',', '.', $v);
}
function jpy($v) { return '¥' . number_format((float)$v, 0, ',' , '.'); }

// ---------------------------
// Lê entradas (POST) com defaults 0
// ---------------------------
$salario_hora        = toFloat($_POST['salario_hora']        ?? '');
$horas_base          = toFloat($_POST['horas_base']          ?? '');

$valor_hora_extra    = toFloat($_POST['valor_hora_extra']    ?? '');
$horas_extra         = toFloat($_POST['horas_extra']         ?? '');

$valor_noturno_hora  = toFloat($_POST['valor_noturno_hora']  ?? '');
$horas_noturnas      = toFloat($_POST['horas_noturnas']      ?? '');

$valor_feriado_hora  = toFloat($_POST['valor_feriado_hora']  ?? '');
$horas_feriado       = toFloat($_POST['horas_feriado']       ?? '');

$valor_adic_hora     = toFloat($_POST['valor_adic_hora']     ?? '');

$bonus_salarial      = toFloat($_POST['bonus_salarial']      ?? '');
$ajudas_adicionais   = toFloat($_POST['ajudas_adicionais']   ?? '');

// ---------------------------
// Cálculos
// ---------------------------
$total_base      = $salario_hora * $horas_base;
$total_extra     = $valor_hora_extra * $horas_extra;
$total_noturno   = $valor_noturno_hora * $horas_noturnas;
$total_feriado   = $valor_feriado_hora * $horas_feriado;
$total_adicional = $valor_adic_hora;
$total_bonus     = $bonus_salarial;
$total_ajudas    = $ajudas_adicionais;

$total_geral = $total_base + $total_extra + $total_noturno + $total_feriado + $total_adicional + $total_bonus + $total_ajudas;

// Para exibir resultados só depois do submit
$foi_submit = ($_SERVER['REQUEST_METHOD'] === 'POST');
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Conferência de Holerite — Banco de Horas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root {
      --bg:linear-gradient(to bottom, #f0f0f0ff, #ffffffff); /* white-900 */
      --card:#f2f2f2;      /* gray-900 */
      --muted:#f0f0f0;     /* slate-400 */
      --text:#262626;      /* gray-200 */
      --accent:#0477BF;    /* green-500 */
      --danger:#ef4444;    /* red-500 */
      --line:#262626;      /* gray-800 */
    }
    *{box-sizing:border-box}
    body{
      margin:0; padding:24px; background:var(--bg); color:var(--text);
      font: 16px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }
    .container{max-width:1000px; margin:0 auto;}
    h1{font-size:clamp(22px,3vw,28px); margin:0 0 8px}
    p.desc{color:var(--muted); margin:0 0 24px}
    .grid{display:grid; grid-template-columns:1fr; gap:16px}
    @media(min-width:900px){ .grid{grid-template-columns: 1fr 1fr} }
    .card{
      background:var(--card); border:1px solid var(--line);
      border-radius:16px; padding:18px;
    }
    .fieldset{display:grid; gap:10px; margin-top:8px}
    .row{display:grid; grid-template-columns: 1fr 1fr; gap:10px}
    label{font-weight:600; font-size:14px}
    input{
      width:100%; padding:10px 12px; background:#212226; color:var(--text);
      border:1px solid var(--line); border-radius:10px; outline:none;
    }
    input:focus{border-color:#262626}
    .hint{font-size:12px; color:#262626}
    .actions{display:flex; gap:10px; margin-top:16px}
    button{
      appearance:none; border:0; padding:12px 16px; border-radius:12px; font-weight:700; cursor:pointer;
    }
    .primary{background:var(--accent); color:#f2f2f2}
    .secondary{background:#f2f2f2; color:var(--text); border:1px solid var(--line)}
    table{width:100%; border-collapse:collapse; margin-top:8px}
    th,td{padding:10px 8px; border-bottom:1px solid var(--line); text-align:left}
    tfoot td{font-weight:800; font-size:18px}
    .money{font-variant-numeric: tabular-nums}
    .pill{display:inline-block; background:#262626; border:1px solid var(--line); border-radius:999px; padding:2px 8px; font-size:12px; color:var(--muted)}
  </style>
</head>
<body>
  <div class="container">
    <h1>Conferência de Holerite</h1>
    <p class="desc">Preencha os campos abaixo. </p>

    <form method="POST" class="grid" action="">
      <!-- Bloco: Salário Base -->
      <div class="card">
        <div class="pill">Salário Base × Horas</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="salario_hora">Salário por hora</label>
              <input type="text" inputmode="decimal" id="salario_hora" name="salario_hora" placeholder="ex.: 1500" value="<?= htmlspecialchars($_POST['salario_hora'] ?? '') ?>">
              <div class="hint">Valor/hora normal</div>
            </div>
            <div>
              <label for="horas_base">Horas base (mês)</label>
              <input type="text" inputmode="decimal" id="horas_base" name="horas_base" placeholder="ex.: 160" value="<?= htmlspecialchars($_POST['horas_base'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Bloco: Hora Extra -->
      <div class="card">
        <div class="pill">Horas Extras × Horas</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="valor_hora_extra">Valor da hora extra</label>
              <input type="text" inputmode="decimal" id="valor_hora_extra" name="valor_hora_extra" placeholder="ex.: 1875 (25% acima)" value="<?= htmlspecialchars($_POST['valor_hora_extra'] ?? '') ?>">
              <div class="hint">Normalmente 25% acima da hora base</div>
            </div>
            <div>
              <label for="horas_extra">Quantidade de horas extras</label>
              <input type="text" inputmode="decimal" id="horas_extra" name="horas_extra" placeholder="ex.: 12" value="<?= htmlspecialchars($_POST['horas_extra'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Bloco: Adicional Noturno -->
      <div class="card">
        <div class="pill">Adicional Noturno × Horas</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="valor_noturno_hora">Valor hora noturna</label>
              <input type="text" inputmode="decimal" id="valor_noturno_hora" name="valor_noturno_hora" placeholder="ex.: 1875 (25% acima)" value="<?= htmlspecialchars($_POST['valor_noturno_hora'] ?? '') ?>">
              <div class="hint">Trabalhos entre 22h e 5h (referência JP: +25%)</div>
            </div>
            <div>
              <label for="horas_noturnas">Horas noturnas</label>
              <input type="text" inputmode="decimal" id="horas_noturnas" name="horas_noturnas" placeholder="ex.: 20" value="<?= htmlspecialchars($_POST['horas_noturnas'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Bloco: Feriados -->
      <div class="card">
        <div class="pill">Feriados × Horas</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="valor_feriado_hora">Valor hora em feriados/finais de semana</label>
              <input type="text" inputmode="decimal" id="valor_feriado_hora" name="valor_feriado_hora" placeholder="ex.: 2025 (35% acima)" value="<?= htmlspecialchars($_POST['valor_feriado_hora'] ?? '') ?>">
              <div class="hint">Referência comum: +35% (confira seu contrato)</div>
            </div>
            <div>
              <label for="horas_feriado">Horas em feriados/finais de semana</label>
              <input type="text" inputmode="decimal" id="horas_feriado" name="horas_feriado" placeholder="ex.: 8" value="<?= htmlspecialchars($_POST['horas_feriado'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Bloco: Adicional Salarial × Horas -->
      <div class="card">
        <div class="pill">Adicional Salarial × Horas</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="valor_adic_hora">Valor total adicional por hora</label>
              <input type="text" inputmode="decimal" id="valor_adic_hora" name="valor_adic_hora" placeholder="ex.: 200" value="<?= htmlspecialchars($_POST['valor_adic_hora'] ?? '') ?>">
              <div class="hint">Ex.: insalubridade, periculosidade etc.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bloco: Bônus e Ajudas -->
      <div class="card">
        <div class="pill">Extras Fixos</div>
        <div class="fieldset">
          <div class="row">
            <div>
              <label for="bonus_salarial">Bônus salarial (fixo)</label>
              <input type="text" inputmode="decimal" id="bonus_salarial" name="bonus_salarial" placeholder="ex.: 5000" value="<?= htmlspecialchars($_POST['bonus_salarial'] ?? '') ?>">
            </div>
            <div>
              <label for="ajudas_adicionais">Ajudas adicionais (fixo)</label>
              <input type="text" inputmode="decimal" id="ajudas_adicionais" name="ajudas_adicionais" placeholder="ex.: 3000 (transporte, alimentação...)" value="<?= htmlspecialchars($_POST['ajudas_adicionais'] ?? '') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Ações -->
      <div class="card" style="grid-column:1/-1">
        <div class="actions">
          <button type="submit" class="primary">Calcular</button>
          <button type="reset" class="secondary" onclick="window.location.href=window.location.pathname">Limpar</button>
        </div>
      </div>
    </form>

    <?php if ($foi_submit): ?>
      <div class="card" style="margin-top:16px">
        <h2 style="margin:0 0 8px">Resumo</h2>
        <table>
          <tbody>
            <tr><td>Salário base</td><td class="money"><?= jpy($total_base) ?></td></tr>
            <tr><td>Horas extras</td><td class="money"><?= jpy($total_extra) ?></td></tr>
            <tr><td>Adicional noturno</td><td class="money"><?= jpy($total_noturno) ?></td></tr>
            <tr><td>Feriados/finais de semana</td><td class="money"><?= jpy($total_feriado) ?></td></tr>
            <tr><td>Adicional salarial</td><td class="money"><?= jpy($total_adicional) ?></td></tr>
            <tr><td>Bônus</td><td class="money"><?= jpy($total_bonus) ?></td></tr>
            <tr><td>Ajudas adicionais</td><td class="money"><?= jpy($total_ajudas) ?></td></tr>
          </tbody>
          <tfoot>
            <tr><td>Total estimado</td><td class="money"><?= jpy($total_geral) ?></td></tr>
          </tfoot>
        </table>
        <p class="hint" style="margin-top:8px">
          * Este é um estimador. Regras reais podem variar por contrato/empresa. Ajuste os valores/hora conforme seu acordo.
        </p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
