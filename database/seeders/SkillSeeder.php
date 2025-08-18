<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('skills')->delete();

        $skills = [
            'Protheus' => [
                'SIGACFG - Configurador', 'SIGAADV - ADVPL', 'SIGAFAT - Faturamento', 'SIGAFIN - Financeiro',
                'SIGACOM - Compras', 'SIGAEST - Estoque', 'SIGACTB - Contabilidade', 'SIGAATF - Ativo Fixo',
                'SIGAPCP - PCP', 'SIGAQDO - Qualidade', 'SIGAMNT - Manutenção', 'SIGATMS - TMS',
                'SIGAWMS - WMS', 'SIGALOJA - Loja', 'SIGATEC - T.I.', 'SIGAJURI - Jurídico',
                'SIGAAGR - Agroindústria', 'SIGACRM - CRM', 'SIGAPON - Ponto Eletrônico',
                'SIGAGPE - Gestão de Pessoal', 'SIGATAF - TAF',
            ],
            'RM' => [
                'Gestão Contábil', 'Gestão de Estoque', 'Gestão de Compras', 'Gestão de Faturamento',
                'Gestão Financeira', 'Gestão Fiscal', 'Gestão de Manutenção', 'Gestão Patrimonial',
                'Planejamento e Controle da Produção', 'Gestão de Imóveis', 'Obras e Projetos',
                'Gestão Educacional', 'Recursos Humanos', 'Gestão de Saúde', 'Desenvolvimento .NET',
            ],
            'Datasul' => [
                'Compras', 'Contratos de Compras', 'Importação', 'Exportação', 'Contas a Receber',
                'Contas a Pagar', 'Fluxo de Caixa', 'Caixa e Bancos', 'Contabilidade', 'Ativo Fixo',
                'Faturamento', 'Administração de Vendas', 'Administração de Materiais',
            ],
            'Geral' => [
                'Gestão de Projetos', 'Comunicação Interpessoal', 'Liderança Técnica', 'Análise de Negócios',
                'SQL Server', 'Oracle Database', 'Metodologias Ágeis (Scrum/Kanban)',
            ]
        ];

        foreach ($skills as $categoria => $nomes) {
            foreach ($nomes as $nome) {
                DB::table('skills')->insert([
                    'nome' => $nome,
                    'categoria' => $categoria,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
