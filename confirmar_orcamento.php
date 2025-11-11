<?php
    include_once("config/process.php");    
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Tela - Preencher CPF</title>
  <style>
    :root{ --bg:#f5f7fb; --card:#ffffff; --accent:#2b6df6; --danger:#e02424; --muted:#6b7280; }
    *{box-sizing:border-box}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background:var(--bg); margin:0; padding:24px; color:#111827}
    .wrap{max-width:520px;margin:48px auto;padding:20px;background:var(--card);border-radius:12px;box-shadow:0 6px 18px rgba(17,24,39,0.06)}
    h1{font-size:20px;margin:0 0 12px}
    p.lead{margin:0 0 18px;color:var(--muted)}
    label{display:block;margin-bottom:6px;font-weight:600}
    .input-row{display:flex;gap:12px;align-items:center}
    input[type="text"]{flex:1;padding:12px 14px;border:1px solid #e6e9ef;border-radius:8px;font-size:15px}
    .help{font-size:13px;color:var(--muted);margin-top:8px}
    .btn{display:inline-block;padding:10px 14px;border-radius:8px;background:var(--accent);color:#fff;border:none;font-weight:600;cursor:pointer}
    .btn[disabled]{opacity:0.6;cursor:not-allowed}
    .msg{margin-top:12px;padding:10px;border-radius:8px;font-size:14px}
    .msg.success{background:#ecfdf5;color:#065f46;border:1px solid #bbf7d0}
    .msg.error{background:#fff1f2;color:var(--danger);border:1px solid #fecaca}
    .small{font-size:13px;color:var(--muted)}
    @media (max-width:520px){.wrap{margin:20px;padding:16px}}
  </style>
</head>
<body>
  <div class="wrap" role="main">
    <h1>Confirmar Orçamento</h1>

    <form action="<?= $BASE_URL ?>config/process.php" method="POST" id="create-form">
        <input type="hidden" name="type" value="confirmar_orcamento">
        <input type="hidden" name="id" value="<?= $_GET["id"]?>">


      <div class="input-row">

            <button type="submit" id="btn-cadastrar" class="btn btn-primary">Confirmar</button>
      </div>
      <div id="feedback" aria-live="polite"></div>
      <p class="help"></p>
    </form>

  </div>

 
 <script>
        function formatarCPF(input) {
            let valor = input.value.replace(/\D/g, ''); // Remove tudo que não for número
            valor = valor.slice(0, 11); // Limita a 11 dígitos
            
            if (valor.length > 9) {
                input.value = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
            } else if (valor.length > 6) {
                input.value = valor.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
            } else if (valor.length > 3) {
                input.value = valor.replace(/(\d{3})(\d{1,3})/, '$1.$2');
            } else {
                input.value = valor;
            }
        }
  </script>
</body>
</html>