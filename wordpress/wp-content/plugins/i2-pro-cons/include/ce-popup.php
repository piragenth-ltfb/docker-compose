<?php
add_filter("mce_external_plugins", "i2pc_ce_enqueue_editor_scripts");
function i2pc_ce_enqueue_editor_scripts($plugin_array)
{
    //enqueue TinyMCE plugin script with its ID.
    $plugin_array["i2pc_ce_popup_button"] =   plugins_url('../dist/js/admin-popup-classic-editor.js', __FILE__);
    return $plugin_array;
}

add_filter("mce_buttons", "i2pc_ce_register_buttons_editor");
function i2pc_ce_register_buttons_editor($buttons)
{
    //register buttons with their id.
    array_push($buttons, "i2pc_ce_popup_button");
    return $buttons;
}

function i2pc_ce_editor_style($hook)
{
    if ( ($hook == 'post-new.php' || $hook == 'post.php') && get_user_option( 'rich_editing' ) == 'true') {
        wp_enqueue_style('i2pc_ce_editor_style', plugins_url('../dist/css/admin-ce-popup.min.css', __FILE__), null, I2PC_CSS_VER);
				add_filter('admin_footer', 'i2pc_ce_editor_popup');
    }
}
add_action('admin_enqueue_scripts', 'i2pc_ce_editor_style');


function i2pc_ce_editor_popup()
{
    ?>
    <div id="i2pc_ce_editor_popup" style="display:none;" class="">
        <div id="i2pc_ce_editor_popup_inner">
            <div class="i2pc-ce-top-head">
                <h2>i2 Pros &amp; Cons</h2>
                <button type="button" class="i2pc-ce-close">&times;</button>
            </div>
            <div class="i2pc-ce-header">
                <div class="i2pc-fl">
                    <div class="clear"></div>
                    <h3><img src="<?php echo plugins_url('../dist/img/i2pc-ce.png', __FILE__); ?>"> <span><b>i2 Pros &amp; Cons</b> <small><small>by <b><i>ThemesFirst</i></b></small></small></span></h3>
                    <div class="clear"></div>
                </div>
                <div class="i2pc-fr">
                    <a href="<?php echo I2PC_MORE_THEMES_PLUGINS_URL; ?>/docs-category/how-to-install-and-use-i2-pros-and-cons-classic-editor/" target="_blank" class="i2pc-button"><i class="dashicons dashicons-editor-help"></i> How to use?</a>
                    <!-- <a href="<?php echo I2PC_MORE_THEMES_PLUGINS_URL; ?>" target="_blank" class="i2pc-button">More WP Themes &amp; Plugins for Amazon</a> -->
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
            <form autocomplete="off" id="i2pc-ce-popup">
                <div class="i2pc-content">
                    <div class="form-group inline-group">
                          <label for="i2pc-cons">Pros Title</label>
                        <input class="form-control" value="Pros" id="pros_title" name="pros_title" />
                    </div>
                    <div class="form-group">
                        <label for="i2pc-pros">Enter Pros</label>
                        <textarea class="form-control" id="i2pc-pros" rows="11"></textarea>
                    </div>
                    <div class="form-group inline-group">
                    <label for="i2pc-cons">Cons Title</label>
                        <input class="form-control" value="Cons" id="cons_title" name="cons_title" />
                    </div>
                    <div class="form-group">
                        <label for="i2pc-cons">Enter Cons</label>
                        <textarea class="form-control" id="i2pc-cons" rows="11"></textarea>
                    </div>
                </div>
                <div class="i2pc-sidebar">
                    <div class="form-group"><label>Show Main Title &nbsp;</label>
                        <label class="switch">
                            <input type="checkbox" name="show_title" id="show_title">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div id="wr-main-title" class="form-group">
                        <label for="i2pc-cons">Main Title</label>
                        <input class="form-control" value="Pros & Cons" id="main-title" />
                    </div>
                    <hr />
                    <div class="form-group">
                    <label for="pros-icon">Pros Icon : </label>
                        <input class="form-control i2-pros-cons-icons" autocomplete="off" id="pros-icon" />
                    </div>
                    <div class="form-group">
                    <label for="cons-icon">Cons Icon : </label>
                        <input class="form-control i2-pros-cons-icons" autocomplete="off" id="cons-icon" />
                    </div>
                    <hr />
                    <div class="form-group" style="display:none;">
                        <label for="i2pc-cons">Use Icon in Heading</label>
                        <select id="use_heading_icon" class="form-control">
                            <option value="">Global</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="i2pc-cons">Pros Title Icon</label>
                        <input class="form-control i2-pros-cons-icons icon-input" id="heading_pros_icon" name="heading_pros_icon" />
                        <small class="help-block">icon will appear when setting 'Use Icon in Heading' are enabled</small>
                    </div>
                    <div class="form-group">
                        <label for="i2pc-cons">Cons Title Icon</label>
                        <input class="form-control i2-pros-cons-icons icon-input" id="heading_cons_icon" name="heading_cons_icon" />
                        <small class="help-block">icon will appear when setting 'Use Icon in Heading' are enabled</small>
                    </div>
                    <hr />
                    <div class="form-group"><label>Show Button &nbsp;</label>
                        <label class="switch">
                            <input type="checkbox" name="show_button" id="show_button">
                            <span class="slider round"></span>
                        </label>
                    </div>
                    <div id="i2pc-wr-btn-amazon">
                        <div class="form-group">
                            <label for="i2pc-cons">Button Icon</label>
                            <input class="form-control i2-pros-cons-icons icon-input" id="button_icon" name="button_icon" />
                        </div>
                        <div class="form-group">
                            <label for="i2pc-cons">Button Text</label>
                            <input class="form-control" id="link_text" name="link_text" />
                        </div>
                        <div class="form-group">
                            <label for="i2pc-cons">Button Link</label>
                            <input class="form-control" id="link" name="link" />
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="i2pc-ce-footer">
                    <a href="#" id="footer-close" class="i2pc-button i2pc-default i2pc-fr">Cancel</a>
                    <a href="#" id="i2pc-ce-submit" class="i2pc-button i2pc-success  i2pc-fr">Insert Shortcode</a>
                </div>
            </form>
        </div>
    </div>
<?php

}
