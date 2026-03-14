<?php
/**
 * Footer Template
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

$footer_type = myco_get_footer_type();

if ($footer_type === 'light') {
    get_template_part('template-parts/footer/footer-light');
} else {
    get_template_part('template-parts/footer/footer-dark');
}
?>

<?php wp_footer(); ?>
</body>
</html>
