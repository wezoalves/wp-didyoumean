<?php

class DidYouMean
{
    private $pspell_link;

    // Método de inicialização do plugin
    public function init()
    {
        // Inicializa o PSpell
        $this->initialize_pspell('pt_BR'); // Padrão: inglês (você pode mudar para 'pt_BR' se necessário)

        // Expor o plugin globalmente
        global $did_you_mean;
        $did_you_mean = $this;

        // Adiciona a ação para verificar a busca
        add_action('pre_get_posts', [$this, 'check_search']);
    }

    // Método para inicializar o PSpell
    private function initialize_pspell($language)
    {
        $pspell_config = pspell_config_create($language);
        pspell_config_mode($pspell_config, PSPELL_FAST);
        $this->pspell_link = pspell_new_config($pspell_config);
    }

    // Método para sugerir o termo correto, se o termo inserido estiver incorreto.
    public function suggest_term($term)
    {
        // Se o termo estiver correto, não faz nada.
        if (pspell_check($this->pspell_link, $term)) {
            return false; // Palavra correta
        }

        // Caso contrário, sugere a palavra correta
        $suggestions = pspell_suggest($this->pspell_link, $term);

        if (!empty($suggestions)) {
            return $suggestions[0]; // Retorna a primeira sugestão
        }

        return false;
    }

    // Método principal que verifica o termo digitado na busca e sugere correções
    public function check_search($query)
    {
        if (is_search() && !is_admin() && $query->is_main_query()) {
            $entered_term = $query->get('s');

            // Verifica se o termo é válido ou precisa de sugestão
            $suggestion = $this->suggest_term($entered_term);

            if ($suggestion && strtolower($entered_term) !== strtolower($suggestion)) {
                // Adiciona uma mensagem ao resultado de busca
                add_filter('the_content', function ($content) use ($suggestion) {
                    return '<p>Did you mean: <strong>' . esc_html($suggestion) . '</strong>?</p>' . $content;
                });
            }
        }
    }
}
