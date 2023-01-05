<?php if(!defined('ABSPATH')) { die('You are not allowed to call this page directly.'); } ?>

<style>
  .notice,
  .error {
    display: none;
  }

  .prli-welcome {
    background-image: url("<?php echo PRLI_IMAGES_URL . '/Confetti.svg'; ?>");
  }
</style>

<div class="wrap prli-welcome">
  <div class="pre-badge"><?php esc_html_e('Welcome to', 'pretty-link'); ?></div>

  <div class="prli-welcome-badge">
    <img src="<?php echo PRLI_IMAGES_URL . '/pl-logo-horiz-RGB.svg'; ?>" alt="<?php esc_attr_e('Welcome to Pretty Links!', 'pretty-link'); ?>">
  </div>

  <p class="prli-welcome-about"><?php esc_html_e('Congratulations, you\'ve just installed the most powerful link management platform for WordPress on Earth!', 'pretty-link'); ?></p>
  <p class="prli-welcome-about"><?php esc_html_e('With Pretty Links, you\'ll no longer need to manage links from a spreadsheet and you\'ll be able to maximize the visibility of your links - these benefits (and more) will make it possible to make more money from your content like never before!', 'pretty-link'); ?></p>

  <div class="prli-center-section">
    <h2><?php esc_html_e('Getting Started is Easy', 'pretty-link'); ?></h2>
    <p><?php esc_html_e('Start by creating your first Pretty Link:', 'pretty-link'); ?></p>
  </div>

  <div class="prli-center-section">

    <div class="prli-welcome-steps">
      <div class="prli-welcome-step">
        <span class="welcome-step-number">1</span>
        <img src="<?php echo PRLI_IMAGES_URL . '/welcome-add-new.png'; ?>" alt="<?php esc_attr_e('Click "Add New Link"', 'pretty-link'); ?>">
        <?php esc_html_e('Click "Add New Link"', 'pretty-link'); ?>
      </div>
      <div class="prli-welcome-step">
        <span class="welcome-step-number">2</span>
        <img src="<?php echo PRLI_IMAGES_URL . '/welcome-enter-url.png'; ?>" alt="<?php esc_attr_e('Enter the URL of your Affiliate Link', 'pretty-link'); ?>">
        <?php esc_html_e('Enter the URL of your Affiliate Link', 'pretty-link'); ?>
      </div>
      <div class="prli-welcome-step">
        <span class="welcome-step-number">3</span>
        <img src="<?php echo PRLI_IMAGES_URL . '/welcome-customize-slug.png'; ?>" alt="<?php esc_attr_e('Customize your Pretty Link Slug', 'pretty-link'); ?>">
        <?php esc_html_e('Customize your Pretty Link Slug', 'pretty-link'); ?>
      </div>
      <div class="prli-welcome-step">
        <span class="welcome-step-number">4</span>
        <img src="<?php echo PRLI_IMAGES_URL . '/welcome-click-update.png'; ?>" alt="<?php esc_attr_e('Click "Update"', 'pretty-link'); ?>">
        <?php esc_html_e('Click "Update"', 'pretty-link'); ?>
      </div>
      <div class="prli-welcome-step">
        <span class="welcome-step-number">5</span>
        <img class="prli-welcome-step" src="<?php echo PRLI_IMAGES_URL . '/welcome-copy-url.png'; ?>" alt="<?php esc_attr_e('Copy the Pretty Link URL', 'pretty-link'); ?>">
        <?php esc_html_e('Copy the Pretty Link URL', 'pretty-link'); ?>
      </div>
    </div>

    <p><?php esc_html_e('Wasn\'t that easy? Now, you can use this link wherever you want!', 'pretty-link'); ?></p>

  </div>

  <div class="pre-badge"><?php esc_html_e('The Power of', 'pretty-link'); ?></div>

  <div class="prli-welcome-badge">
    <img src="<?php echo PRLI_IMAGES_URL . '/plp-dialog-logo.svg'; ?>" alt="<?php esc_attr_e('The Power of Pretty Links Pro', 'pretty-link'); ?>">
  </div>

  <div class="prli-center-section">
    <p><?php _e('There are many reasons that premium users of Pretty Links <br> are able to take their business to the next level:', 'pretty-link'); ?></p>
  </div>

  <div class="prlip-reasons">
    <div class="prlip-reason">
      <div class="reason-image"><img src="<?php echo PRLI_IMAGES_URL . '/Swiss_Army_Knife.png'; ?>" alt=""></div>
      <div class="reason-content">
        <div class="reason-title"><h3><?php esc_html_e('Automated, Site-Wide Link Placement (Keyword Replacement)', 'pretty-link'); ?></h3></div>
        <div class="reason-desc">
          <p><?php esc_html_e('Imagine if you never had to hand-edit links, copy and paste from a spreadsheet, or actively have to keep up with you links ever again. Well that time has come! Now with Pretty Links, all you have to do is create your links and let Pretty Links do the rest!', 'pretty-link'); ?></p>
          <p><?php esc_html_e('Pretty Links will scan your content for the keywords or URLs that you want to target and will replace them with Pretty Links automatically! This will save you tons of time so you can focus on growing your business!', 'pretty-link'); ?></p>
        </div>
      </div>
    </div>
    <div class="prlip-reason">
      <div class="reason-image"><img src="<?php echo PRLI_IMAGES_URL . '/PL-Categories.png'; ?>" alt=""></div>
      <div class="reason-content">
        <div class="reason-title"><h3><?php esc_html_e('Categories & Tags', 'pretty-link'); ?></h3></div>
        <div class="reason-desc">
          <p><?php esc_html_e('When you are dealing with a large number of links, it can be easy to be overwhelmed and confused by which links to you planned to use where. It\'s now easier than ever to organize your links and group them any way that you like for ultimate productivity and monetization!', 'pretty-link'); ?></p>
        </div>
      </div>
    </div>
    <div class="prlip-reason">
      <div class="reason-image"><img src="<?php echo PRLI_IMAGES_URL . '/redirection-link-menu_324_original3.png'; ?>" alt=""></div>
      <div class="reason-content">
        <div class="reason-title"><h3><?php esc_html_e('Advanced Redirect Types', 'pretty-link'); ?></h3></div>
        <div class="reason-desc">
          <p><?php esc_html_e('Lite users can take advantage of 301 & 302 server side redirects but our pro users can also redirect with cloaking, JavaScript, Meta-refresh and more.', 'pretty-link'); ?></p>
          <p><?php esc_html_e('So if you have social media links, a landing page, or a new page or post you\'d like to redirect your customers to, this feature will definitely come in handy!', 'pretty-link'); ?></p>
        </div>
      </div>
    </div>
    <div class="prlip-reason">
      <div class="reason-image"><img src="<?php echo PRLI_IMAGES_URL . '/dynamic-redirect-types.jpg'; ?>" alt=""></div>
      <div class="reason-content">
        <div class="reason-title"><h3><?php esc_html_e('Dynamic Redirect Types', 'pretty-link'); ?></h3></div>
        <div class="reason-desc">
          <p><?php esc_html_e('Would you like your Pretty Link to redirect somewhere custom depending on what country your user is in, device they\'re using, time they\'re clicking the link or just randomly? Our pro users can do this easily with our Dynamic Redirections!', 'pretty-link'); ?></p>
          <p><?php esc_html_e('This feature is excellent if you are running a time-sensitive sales, want to create custom content for you customers, create stellar landing pages for customers in different countries!', 'pretty-link'); ?></p>
        </div>
      </div>
    </div>
    <div class="prlip-reason">
      <div class="reason-image"><img src="<?php echo PRLI_IMAGES_URL . '/import-export.png'; ?>" alt=""></div>
      <div class="reason-content">
        <div class="reason-title"><h3><?php esc_html_e('Import and Export Links', 'pretty-link'); ?></h3></div>
        <div class="reason-desc">
          <p><?php esc_html_e('Export your links to a spreadsheet or import them en masse - our pro users can do this with ease. Simply download your spreadsheet, upload to WordPress, and your links are automatically added to Pretty Links!', 'pretty-link'); ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="prli-center-section">
    <h3 style="color: #0D459C;"><?php esc_html_e('The list goes on and on', 'pretty-link'); ?></h3>
    <p><?php _e('Our premium editions of Pretty Links are a HUGE upgrade from Lite. <br> Don’t miss out on our critical PRO benefits!', 'pretty-link'); ?></p>
  </div>

  <div class="prli-center-section prli-button-section">
    <p><a href="https://prettylinks.com/pl/welcome-page/upgrade" class="button button-primary button-upgrade"><?php esc_html_e('Upgrade to Pretty Links Pro Now', 'pretty-link'); ?></a></p>
    <p style="color: #0D459C;"><strong><?php esc_html_e('Upgrade NOW and get $50 off of your subscription!!!', 'pretty-link'); ?></strong></p>
  </div>

</div>
