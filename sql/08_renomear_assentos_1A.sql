-- Renomeia os assentos antigos (01, 02, 03...) para fila+letra (1A, 1B, 1C, 1D, 2A...).
-- FAÇA BACKUP ANTES. Requer MySQL 8+ ou MariaDB 10.2+ (funções de janela).
-- Só mexe em assentos cujo número é apenas dígitos; os que já são 1A, 2B... ficam como estão.
-- Configuração 2+2 -> 4 lugares por fila (A-D); 2+1 -> 3 lugares por fila (A-C).
-- As reservas ligam-se ao assento pelo id, por isso as reservas existentes não se perdem.
UPDATE assento s
JOIN (
  SELECT x.id_assento,
         CONCAT(CEIL(x.rn / x.pf), ELT(MOD(x.rn - 1, x.pf) + 1, 'A', 'B', 'C', 'D')) AS novo
  FROM (
    SELECT t.id_assento,
           ROW_NUMBER() OVER (PARTITION BY t.id_autocarro ORDER BY CAST(t.numero AS UNSIGNED)) AS rn,
           IF(a.configuracao_assentos = '2+1', 3, 4) AS pf
    FROM assento t
    JOIN autocarro a ON a.id_autocarro = t.id_autocarro
    WHERE t.numero REGEXP '^[0-9]+$'
  ) x
) n ON n.id_assento = s.id_assento
SET s.numero = n.novo;

-- Verificação: deve mostrar 1A, 1B, 1C, 1D, 2A...
SELECT a.codigo_frota, SUBSTRING_INDEX(GROUP_CONCAT(s.numero ORDER BY s.id_assento), ',', 8) AS primeiros
FROM autocarro a JOIN assento s ON s.id_autocarro = a.id_autocarro GROUP BY a.id_autocarro;
