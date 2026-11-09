// assets/js/painel.js - comportamentos comuns do painel
document.addEventListener('DOMContentLoaded', () => {
  // mostrar / ocultar palavra-passe
  document.querySelectorAll('[data-alternar-senha]').forEach(b => {
    b.addEventListener('click', () => {
      const c = document.getElementById(b.dataset.alternarSenha);
      const mostrar = c.type === 'password';
      c.type = mostrar ? 'text' : 'password';
      b.textContent = mostrar ? 'Ocultar' : 'Mostrar';
    });
  });

  // botões que mudam a acção do formulário (ex.: remover foto)
  document.querySelectorAll('[data-acao]').forEach(b => {
    b.addEventListener('click', () => {
      const f = b.closest('form');
      f.querySelector('input[name=acao]').value = b.dataset.acao;
      f.submit();
    });
  });

  // pré-visualização da foto escolhida
  const ficheiro = document.getElementById('foto-input');
  const previa   = document.getElementById('foto-previa');
  if (ficheiro && previa) {
    ficheiro.addEventListener('change', () => {
      const f = ficheiro.files[0];
      if (!f) return;
      previa.innerHTML = '';
      const img = document.createElement('img');
      img.src = URL.createObjectURL(f);
      previa.appendChild(img);
    });
  }

  // força da palavra-passe
  const nova = document.getElementById('nova_senha');
  if (nova) {
    const barras = document.querySelectorAll('.forca span');
    const texto = document.getElementById('forca-texto');
    const pct = document.getElementById('forca-pct');
    const cores = ['#d92d3a', '#f08a24', '#e6b800', '#12a150'];
    const nomes = ['Palavra-passe fraca', 'Palavra-passe razoável', 'Palavra-passe boa', 'Palavra-passe forte'];
    const avaliar = () => {
      const v = nova.value;
      let n = 0;
      if (v.length >= 8) n++;
      if (/[a-z]/.test(v) && /[A-Z]/.test(v)) n++;
      if (/\d/.test(v)) n++;
      if (/[^A-Za-z0-9]/.test(v)) n++;
      barras.forEach((b, i) => b.style.background = i < n ? cores[Math.max(n - 1, 0)] : '');
      texto.textContent = v ? nomes[Math.max(n - 1, 0)] : '';
      pct.textContent = v ? (n * 25) + '% segura' : '';
    };
    nova.addEventListener('input', avaliar);
  }
});
