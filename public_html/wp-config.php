<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações
// com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'pinheiro_dados');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'pinheiro_dados');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'bios2805');

/** Nome do host do MySQL */
define('DB_HOST', 'pinheiro_dados.mysql.dbaas.com.br');

/** Charset do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '8fv?:5Dh6W6eJ4L`Cby|W$S|C~ ash)w$W?8{RQT8&?JS6-(#@pF1hZ0!{RXLH[t');
define('SECURE_AUTH_KEY',  '2;<_7oySfC1h|>cDc=;C$97x[&m[[UASGCbr7aUsX!?;pFqy+`U/|=w@;a(-?q)<');
define('LOGGED_IN_KEY',    'ALTvvlveQQ@Tu.>3m`@Tba[Qb:q=w?>mwS~}Tu>eZ%=}]|C{L;ywxuXkJX^ybNHc');
define('NONCE_KEY',        'y+^-4;=D6/w1J15F/XG>&YZKa%2q:aIX[+uX!c~%;z5`)d4f09@iV!{xU*Fs:n%(');
define('AUTH_SALT',        'Qln9_ER:RR*UAC<x{/Y }Lbe~F&|Fe?&9mB8|1/eF8|*1#1ma{Xq]4%MI-5*?VkP');
define('SECURE_AUTH_SALT', 'k0NIdW(nIQjA+K(d~w!K9By+0*T]HM+pX0+=(g(i 0k/xYjRRUxRh|}gGanRa|Y>');
define('LOGGED_IN_SALT',   'K3Tw$y-dNf[-I!8!40LNl|o4jXn,N!)A!e#cza7t>Vb3f21dmR,{&W.R^n_unG=W');
define('NONCE_SALT',       'P*P11o!F547Mt[-)9Ao$btzX+>~B`BF|g8u2%6/3=eW|iv..q?qoTJ=^p;wk.ra0');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
