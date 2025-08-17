<x-guest-layout>
    <div class="px-6 py-8">
        <h2 class="font-bold text-2xl text-gray-800 mb-4 text-center">Termo de Aceite para Tratamento de Dados Pessoais</h2>
        
        <div class="prose prose-sm max-w-none text-gray-600 h-96 overflow-y-auto border p-4 rounded-md">
            <h3>Bem-vindo(a) ao Portal de Apontamento de Horas da PROGMUD!</h3>
            <p>Este documento tem como objetivo informá-lo(a) sobre como seus dados pessoais serão coletados, armazenados e utilizados ao acessar e utilizar nosso Portal, em conformidade com a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 – LGPD).</p>
            
            <h4>1. O que são e quais dados serão coletados?</h4>
            <p>Dados pessoais são informações que identificam ou podem identificar uma pessoa natural. Para a sua utilização do Portal de Apontamento de Horas, coletaremos os seguintes dados:</p>
            <ul>
                <li><strong>Dados de Identificação e Contato:</strong> Nome completo, CPF, e-mail corporativo ou pessoal, número de telefone.</li>
                <li><strong>Dados Profissionais:</strong> Informações sobre sua atuação como consultor terceirizado da PROGMUD (ex: projetos alocados, clientes atendidos).</li>
                <li><strong>Dados de Registro de Atividade:</strong> Horas trabalhadas, datas de apontamento, descrição das atividades realizadas, projeto(s) e cliente(s) relacionados ao apontamento.</li>
                <li><strong>Dados de Acesso ao Portal:</strong> Endereço IP, data e hora de acesso, informações sobre o dispositivo utilizado (ex: tipo de navegador, sistema operacional).</li>
            </ul>

            <h4>2. Para que finalidade seus dados serão utilizados?</h4>
            <p>Seus dados pessoais serão utilizados exclusivamente para as seguintes finalidades relacionadas ao seu trabalho como consultor(a) terceirizado(a) da PROGMUD.</p>
            <ul>
                <li><strong>Gestão de Apontamento de Horas:</strong> Permitir o registro, visualização, aprovação e monitoramento das horas trabalhadas por você nos projetos.</li>
                <li><strong>Faturamento e Remuneração:</strong> Utilizar as horas apontadas para processamento de pagamentos, faturamento aos clientes e cumprimento de obrigações fiscais e contratuais.</li>
                <li><strong>Gerenciamento de Projetos:</strong> Auxiliar na gestão e acompanhamento do progresso dos projetos em que você está alocado(a).</li>
                <li><strong>Comunicação:</strong> Entrar em contato com você sobre assuntos relacionados aos seus apontamentos, projetos ou informações importantes do Portal.</li>
                <li><strong>Segurança do Portal:</strong> Monitorar acessos e atividades para garantir a segurança dos dados, prevenir fraudes e detectar atividades incomuns.</li>
                <li><strong>Cumprimento de Obrigações Legais e Regulatórias:</strong> Atender a requisitos legais, fiscais ou regulatórios aplicáveis às nossas operações.</li>
            </ul>

            <h4>3. Com quem seus dados poderão ser compartilhados?</h4>
            <p>Seus dados poderão ser compartilhados, sempre de forma segura e apenas o estritamente necessário, com:</p>
            <ul>
                <li><strong>Clientes da PROGMUD:</strong> Apenas os dados de registro de atividade (horas, descrição das atividades, projetos e clientes) relevantes para o faturamento e acompanhamento dos serviços prestados. Seu CPF ou e-mail pessoal não serão compartilhados com os clientes, salvo em casos de exigência legal ou contratual específica.</li>
                <li><strong>Prestadores de Serviço Terceirizados:</strong> Empresas que nos apoiam em atividades essenciais, como processamento de pagamentos (se aplicável) e serviços de infraestrutura tecnológica (ex: hospedagem de servidores), que estão contratualmente obrigadas a proteger seus dados.</li>
                <li><strong>Autoridades Governamentais:</strong> Em caso de obrigações legais ou requisição judicial.</li>
            </ul>

            <h4>4. Por quanto tempo seus dados serão armazenados?</h4>
            <p>Seus dados serão armazenados pelo tempo necessário para cumprir as finalidades para as quais foram coletados, bem como para atender a requisitos legais e regulatórios (ex: prazos de guarda de documentos fiscais e contratuais). Após este período, seus dados serão eliminados de forma segura ou anonimizados.</p>

            <h4>5. Como seus dados são protegidos?</h4>
            <p>PROGMUD adota medidas de segurança técnicas e administrativas robustas para proteger seus dados pessoais contra acesso não autorizado, perda, alteração ou divulgação indevida.</p>

            <h4>6. Quais são os seus direitos como titular dos dados?</h4>
            <p>De acordo com a LGPD, você possui os seguintes direitos em relação aos seus dados pessoais: Confirmação e Acesso, Correção, Anonimização, Bloqueio ou Eliminação, Portabilidade, Eliminação, Informação e Revogação do Consentimento. Para exercer qualquer um desses direitos, entre em contato conosco através do e-mail: financeiro@progmud.com.br</p>
        </div>

        <form method="POST" action="{{ route('termo.accept') }}" class="mt-6">
            @csrf
            
            <div class="block">
                <label for="aceite" class="inline-flex items-center">
                    <input id="aceite" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="aceite" required>
                    <span class="ms-2 text-sm text-gray-600 font-semibold">Li e Aceito os Termos de Aceite para o Tratamento de Dados Pessoais.</span>
                </label>
                <x-input-error :messages="$errors->get('aceite')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    Continuar e Acessar o Portal
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>