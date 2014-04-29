<?php
/**
 * QuickLaunch
 * Defines custom controls for WP's Customize screen.
 *
 * @package QuickLaunch
 * @version 1.0
 * @since 2.2
 * @author brux <brux.romuar@gmail.com>
 */
function ql_define_custom_controls($wp_customize)
{

    class QL_Slider_Control extends WP_Customize_Control
    {

        public $type = 'slider';

        public $min = null;

        public $max = null;

        public $step = null;

        public function render_content()
        {
        ?>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <input type="text" value="<?php echo esc_attr($this->value()); ?>" class="ql-slider"<?php if ( $this->min ): ?> data-min="<?php echo $this->min; ?>"<?php endif; ?><?php if ( $this->max ): ?> data-max="<?php echo $this->max; ?>"<?php endif; ?><?php if ( $this->step ): ?> data-step="<?php echo $this->step; ?>"<?php endif; ?><?php $this->link(); ?>>
        <?php
        }

    }

    class QL_Gradient_BG_Control extends WP_Customize_Control
    {

        public $type = 'gradient_bg';

        public function render_content()
        {
        ?>
            <div class="gradient-picker">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <label><input type="checkbox" class="gradient-toggle" <?php checked(false, !$this->value()); ?>> Use gradient as background</label>
                <ul class="gradient-sets">
                    <?php foreach ( $this->choices as $color ): ?>
                    <li><a href="#" class="<?php echo $color; ?>"></a></li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> class="gradient-value">
            </div>
        <?php
        }

    }

}
add_action('customize_register', 'ql_define_custom_controls');
