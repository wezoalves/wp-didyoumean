<?php

/**
 * Plugin Name: Did You Mean
 * Description: Spellcheck Suggestion with Object-Oriented Programming.
 * Version: 1.0
 * Author: Weslley Alves
 */

// Evitar o acesso direto ao arquivo
if (!defined('ABSPATH')) {
  exit;
}

// Carrega a classe principal do plugin
require_once plugin_dir_path(__FILE__) . 'src/DidYouMean.php';

// Instancia o plugin e chama o método de inicialização
function did_you_mean_run()
{
  $plugin = new DidYouMean();
  $plugin->init();
}
add_action('plugins_loaded', 'did_you_mean_run');

// Função global para sugerir um termo com base na busca
function did_you_mean_suggest($term)
{
  global $did_you_mean;

  if ($did_you_mean) {
    return $did_you_mean->suggest_term($term);
  }

  return false;
}
