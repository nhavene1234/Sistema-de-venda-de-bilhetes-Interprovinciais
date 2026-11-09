// assets/js/auth.js - login e recuperação de palavra-passe
document.addEventListener('DOMContentLoaded', () => {
  const mmss = n => String(Math.floor(n / 60)).padStart(2, '0') + ':' + String(n % 60).padStart(2, '0');

  // mostrar / ocultar palavra-passe
  document.querySelectorAll('[data-alternar-senha]').forEach(b => {
    b.addEventListener('click', () => {
      const c = document.getElementById(b.dataset.alternarSenha);
      const mostrar = c.type === 'password';
      c.type = mostrar ? 'text' : 'password';
      if (b.querySelector('svg')) {            // botão com ícone de olho (login)
        b.classList.toggle('visivel', mostrar);
        b.setAttribute('aria-label', mostrar ? 'Ocultar palavra-passe' : 'Mostrar palavra-passe');
      } else {
        b.textContent = mostrar ? 'Ocultar' : 'Mostrar';
      }
    });
  });

  // perfis de demonstração (preenchem o formulário)
  document.querySelectorAll('[data-demo-email]').forEach(b => {
    b.addEventListener('click', () => {
      document.getElementById('email').value = b.dataset.demoEmail;
      document.getElementById('senha').value = b.dataset.demoSenha;
    });
  });

  // contagem do bloqueio de conta
  const contagem = document.getElementById('contagem');
  if (contagem) {
    let s = parseInt(contagem.dataset.segundos, 10);
    contagem.textContent = mmss(s);
    const t = setInterval(() => {
      s--;
      if (s <= 0) { clearInterval(t); location.reload(); return; }
      contagem.textContent = mmss(s);
    }, 1000);
  }

  // reenvio do código
  const espera = document.getElementById('reenviar-espera');
  const botao  = document.getElementById('btn-reenviar');
  if (espera && botao) {
    let s = parseInt(espera.dataset.segundos, 10);
    const alvo = espera.querySelector('b');
    const t = setInterval(() => {
      s--;
      if (s <= 0) { clearInterval(t); espera.hidden = true; botao.hidden = false; return; }
      alvo.textContent = s;
    }, 1000);
  }

  // caixas do código de 6 dígitos
  const caixas = [...document.querySelectorAll('.codigo-caixa')];
  caixas.forEach((c, i) => {
    c.addEventListener('input', () => {
      c.value = c.value.replace(/\D/g, '').slice(0, 1);
      if (c.value && caixas[i + 1]) caixas[i + 1].focus();
    });
    c.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !c.value && caixas[i - 1]) caixas[i - 1].focus();
    });
    c.addEventListener('paste', e => {
      const t = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
      if (!t) return;
      e.preventDefault();
      t.split('').forEach((ch, k) => { if (caixas[k]) caixas[k].value = ch; });
      caixas[Math.min(t.length, 5)].focus();
    });
  });
  if (caixas[0]) caixas[0].focus();
});
