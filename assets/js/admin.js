// assets/js/admin.js - modais, filtros, confirmações e matriz de permissões
document.addEventListener('DOMContentLoaded', () => {
  // modais (<dialog>)
  document.querySelectorAll('[data-abrir]').forEach(b =>
    b.addEventListener('click', () => document.getElementById(b.dataset.abrir).showModal()));
  document.querySelectorAll('[data-fechar]').forEach(b =>
    b.addEventListener('click', () => b.closest('dialog').close()));
  document.querySelectorAll('dialog.modal').forEach(d =>
    d.addEventListener('click', e => { if (e.target === d) d.close(); }));

  // filtros que submetem ao escolher
  document.querySelectorAll('form.filtros select').forEach(s =>
    s.addEventListener('change', () => s.form.submit()));

  // confirmação antes de acções sensíveis
  document.querySelectorAll('form[data-confirmar]').forEach(f =>
    f.addEventListener('submit', e => { if (!confirm(f.dataset.confirmar)) e.preventDefault(); }));

  // novo utilizador: caixa informativa conforme o perfil
  const perfil = document.getElementById('novo-perfil');
  if (perfil) {
    const info  = document.getElementById('info-perfil');
    const grupo = document.getElementById('grupo-transp');
    const sel   = document.getElementById('nova-transp');
    const textos = {
      gestor:        ['Permissões limitadas', 'O Gestor só verá dados desta transportadora (rotas, relatórios e vendas).'],
      bilheteiro:    ['Permissões limitadas', 'O Bilheteiro só poderá vender bilhetes e consultar lugares desta transportadora.'],
      cobrador:      ['Permissões limitadas', 'O Cobrador só valida bilhetes das viagens que o gestor da transportadora lhe atribuir. A transportadora pode ficar em branco: o gestor associa-o depois.'],
      administrador: ['Acesso total', 'O Administrador gere utilizadores e permissões de todo o sistema. Atribua com cuidado.'],
    };
    const actualizar = () => {
      const t = textos[perfil.value];
      info.innerHTML = '<strong>' + t[0] + '</strong>' + t[1];
      const semTransp = perfil.value === 'administrador';
      const opcional = perfil.value === 'cobrador';   // o gestor associa o cobrador à transportadora
      grupo.hidden = semTransp;
      sel.required = !semTransp && !opcional;
      const asterisco = grupo.querySelector('.requerido');
      if (asterisco) asterisco.hidden = opcional;
      sel.options[0].textContent = opcional ? 'Sem transportadora (o gestor associa)' : 'Seleccione...';
    };
    perfil.addEventListener('change', actualizar);
    actualizar();
  }

  // matriz de permissões: guarda cada alteração de imediato
  const matriz = document.getElementById('matriz');
  if (matriz) {
    matriz.querySelectorAll('input[data-perm]').forEach(i => {
      i.addEventListener('change', async () => {
        const corpo = new URLSearchParams({
          csrf: matriz.dataset.csrf, perfil: i.dataset.perfil,
          id_permissao: i.dataset.perm, concedida: i.checked ? 1 : 0,
        });
        try {
          const r = await fetch(matriz.dataset.url, { method: 'POST', body: corpo });
          const j = await r.json();
          if (!j.ok) throw new Error();
        } catch (e) {
          i.checked = !i.checked;
          alert('Não foi possível guardar a alteração. Tente novamente.');
        }
      });
    });
  }
});
