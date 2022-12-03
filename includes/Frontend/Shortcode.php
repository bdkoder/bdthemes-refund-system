<?php
/**
 * Description of Shortcode
 *
 * @author Shahidul Islam
 */

namespace Bdthemes\RefundSystem\Frontend;

/**
 * Shortcode handler class
 */
class Shortcode {
    
    /**
     * Initializes the class
     */
    public function __construct() {
        add_shortcode('nas-academy', [$this, 'render_shortcode']);
    }
    
    /**
     * 
     * @param arry $atts
     * @param string $content
     * 
     * @return string
     */
    public function render_shortcode($atts, $content = '') {
        return 'Hello from Shortcode';
    }
}
