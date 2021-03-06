/**
 * Essential is a clean and customizable theme.
 *
 * @package     theme_essential
 * @copyright   2016 Gareth J Barnard
 * @copyright   2015 Gareth J Barnard
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* jshint ignore:start */
define(['jquery', 'theme_bootstrapbase/bootstrap', 'core/log'], function($, boot, log) {

    "use strict"; // jshint ;_;

    log.debug('Essential affix AMD');

    return {
        init: function() {
            $(document).ready(function($) {
                if ($("#essentialnavbar").length) {
                    $("#essentialnavbar").affix();
                }
            });
            log.debug('Essential affix AMD init');
        }
    };
});
/* jshint ignore:end */
