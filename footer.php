<?php
/**
 * Footer
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<footer
    id="colophon"
    class="site-footer"
>

    <?php
    get_template_part(
        'template-parts/footer/footer-main'
    );
    ?>

    <?php
    get_template_part(
        'template-parts/footer/subfooter'
    );
    ?>

</footer>

<?php wp_footer(); ?>

</body>
</html>